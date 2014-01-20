jQuery(document).ready(function(){

// click  or submit api
    jQuery("#md_reserve_status_approve").click(function(){

        var reserve_approve_msg  = 'Do you want to confirm this reservation? Note: This cannot be undone later';
        var md_reserve_approve_confirm = confirm(reserve_approve_msg);


        var reserve_id = jQuery("#md_reserve_status_approve").parent().siblings(".md_title").text();
        var product_id = jQuery('#md_reserve_status_approve').parent().siblings('.massdata_reserved_product').text();
        var user_id = jQuery('#md_reserve_status_approve').parent().siblings('.massdata_reserver').text();
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

        if(md_reserve_approve_confirm === true){
            jQuery.post(ajaxurl, data, function(response){
                // jQuery("#md_reserve_status_approve").parent().parent().css("background-color", "green").fadeOut( "fast");

                if(response == 500){
                    alert('Could not approve this reservation. Reservation exceeds stock count.');
                }else if(response == 404){
                    alert("This reservation no longer exist. Please refresh the page to view happened changes");
                }else if(response == 1){
                    alert('Reservation successfully applied');
                }else{
                    alert(response);

                }
            });
        }

        return false;

    });

    jQuery("#md_reserve_status_cancel").click(function(){


        var reserve_cancel_msg = 'Do you want to cancel this reservation? Note: This cannot be undone later';
        var md_reserve_cancel_confirm = confirm(reserve_cancel_msg);
        var reserve_id = jQuery('#md_reserve_status_cancel').parent().siblings(".md_title").text();
        var product_id = jQuery('#md_reserve_status_cancel').parent().siblings('.massdata_reserved_product').text();
        var user_id = jQuery('#md_reserve_status_cancel').parent().siblings('.massdata_reserver').text();
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
                // jQuery("#md_reserve_status_cancel").parent().parent().css("background-color", "red").fadeOut( "fast");
                alert(response);
            });
        }

        return false;
    });


});
