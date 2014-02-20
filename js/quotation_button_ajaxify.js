jQuery(document).ready(function(){

// click  or submit api
    jQuery(".md_quote_status_approve").click(function(e){
        e.preventDefault();

        var quote_status_msg  = 'Do you want to confirm this quotation? Note: This cannot be undone later';
        var md_quote_approve_confirm = confirm(quote_status_msg);
        var quote_id = jQuery(this).attr("massdata_quote_id");

        data = {
            action : 'get_quote_status',
            quote_id : quote_id
        };

        if(md_quote_approve_confirm === true){

            jQuery.post(ajaxurl, data, function(response){
                if(response === 0){
                    alert('Quote confirmation failed. Please refresh page....');
                }else{
                    window.location.href = response;
                }
            });
        }
        return false;
    });



});
