jQuery(document).ready(function(){

// click  or submit api
    jQuery(".md_reserve_status_approve").click(function(){

        var reserve_approve_msg  = 'Do you want to confirm this reservation? Note: This cannot be undone later';
        var md_reserve_approve_confirm = confirm(reserve_approve_msg);
        var reserve_id = jQuery(this).parent().siblings(".md_title").text();
        var product_id = jQuery(this).parent().siblings('.massdata_reserved_product').text();
        var user_id = jQuery(this).parent().siblings('.massdata_reserver').text();
        reserve_id = reserve_id.split('Reservation#');
        user_id = user_id.split(':');

        data = {
            action : 'get_approve_status',
            data : {
                reserve_id : reserve_id[1],
                product_id : product_id,
                user_id : user_id[0]
            }
        };

        if(md_reserve_approve_confirm == true){
            jQuery.post(ajaxurl, data, function(response){
                response = jQuery(parseJSON(response));


                if(response.response_status == 500){
                    alert('Could not approve this reservation. Reservation exceeds stock count.');
                }else if(response.response_status == 404){
                    alert("This reservation no longer exist. Please refresh the page to view happened changes");
                }else if(response.response_status == 1){

                    jQuery(".md_reserve_status_approve")
                    .parent()
                    .parent()
                    .find(".md_title")
                    .filter(function(index){
                        return jQuery(this).text() == "Reservation#"+response.reserve_id
                    })
                    .parent()
                    .fadeOut()
                    .css('background-color', 'green');

                    alert('Reservation successfully applied');
                }else if(response.response_status === 0){
                    alert('Reservation does not exist. May have been cancelled');
                }else{
                    alert('Something went wrong. Thats all I know');
                }
            });
        }

        return false;

    });

    jQuery(".md_reserve_status_cancel").click(function(){
        var reserve_cancel_msg = 'Do you want to cancel this reservation? Note: This cannot be undone later';
        var md_reserve_cancel_confirm = confirm(reserve_cancel_msg);
        var reserve_id = jQuery(this).parent().siblings(".md_title").text();
        var product_id = jQuery(this).parent().siblings('.massdata_reserved_product').text();
        var user_id = jQuery(this).parent().siblings('.massdata_reserver').text();
        reserve_id = reserve_id.split('Reservation#');
        user_id = user_id.split(':');

        data = {
            action : 'get_cancel_status',
            reserve_id : reserve_id[1],
            product_id : product_id,
            user_id : user_id[0]
        };

        if(md_reserve_cancel_confirm === true){
            jQuery.post(ajaxurl, data, function(response){
                response = jQuery.parseJSON(response);

                if(response.response_status == 1){

                    jQuery(".md_reserve_status_cancel")
                    .parent()
                    .parent()
                    .find(".md_title")
                    .filter(function(index){
                        return jQuery(this).text() === "Reservation#"+response.reserve_id
                    })
                    .parent()
                    .fadeOut()
                    .css('background-color', 'red');

                    alert('Reservation has been cancelled');
                }else if(response.response_status == 0){
                    alert('Reseervation does not exist. May have been cancelled');
                }else{
                    alert('Something went wrong. Thats all I know');
                }
            });
        }
        return false;
    });


});
