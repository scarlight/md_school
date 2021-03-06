var cs = jQuery.noConflict();

cs(document).ready(function(){

    var doc = document.documentElement;
    doc.setAttribute('data-useragent', navigator.userAgent);
    var isIE11 = !!navigator.userAgent.match(/Trident\/7\./);
    if(isIE11){
        cs('body').addClass("ie11");
    }

    cs('#single-product-tabs a:first').tab('show');
    cs('#single-product-tabs a').click(function(e) {
        e.preventDefault()
        cs(this).tab('show');
    });

    cs('ul#top-mainmenu').
        supersubs({
            minWidth:   14,
            maxWidth:   30,
            extraWidth: 1
        }).superfish({
            delay:400,
            animation:{
                height:"show",
                opacity:"show"
            },
            animationOut:{
                height:"hide",
                opacity:"hide"
            },
            speed: 150,
            cssArrows: true
        });

    cs('.selectpicker').selectpicker({ style: 'btn btn-default' });
    cs('.fileinput').fileinput();
    cs('.datepicker').datepicker({
        format:'dd/mm/yyyy',
        weekStart:1,
        autoclose: false,
        startDate: 'today',
        todayHighlight: true
    })
    cs('#sidebar ul').affix({offset: {top: 180}});
    cs('#sidebar a').tooltip({
        animation: true,
        placement: 'left',
        trigger: 'hover',
        title: function(){
            return cs(this).text();
        },
        delay: { show: 0, hide: 150 }
    });

    // -----------------------------------------------------------

    cs("#login a.login").on("click", function(e){
        e.preventDefault();
        cs('#login-modal').modal({
            toggle:true,
            focusOn: "input:first",
            width: "400px",
            height: "auto",
            backdrop: "static"
        });
    });
    cs("#login button.login-modal-close").on("click", function(e){
        e.preventDefault();
        cs('#login-modal').modal("hide");
    });

    cs("#login a.signup").on("click", function(e){
        e.preventDefault();
        cs('#signup-modal').modal({
            toggle:true,
            focusOn: "input:first",
            width: "510px",
            height: "auto",
            backdrop: "static"
        });
    });
    cs("#login button.signup-modal-close").on("click", function(e){
        e.preventDefault();
        cs('#signup-modal').modal("hide");
    });

    cs("a.blue-text-signup").on("click", function(e){
        e.preventDefault();

        cs('#login-modal').on("hidden.bs.modal", function(e){
            cs('#signup-modal').modal({
                toggle:true,
                focusOn: "input:first",
                width: "510px",
                height: "auto",
                backdrop: "static"
            });
        });

        cs('#login-modal').modal("hide");
    });

    cs("a.blue-text-reset-password").on("click", function(e){
        e.preventDefault();
    });

    var md_signin_err = cs('#md_signin_err').text();
    if(md_signin_err.length > 0){
        cs('#signup-modal').modal("hide");
        cs('#login-modal').modal({
            toggle:true,
            focusOn: "input:first",
            width: "400px",
            height: "auto",
            backdrop: "static"
        });
    }

    var md_signup_err = cs('#md_signup_err').text();
    if(md_signup_err.length > 0){
        cs('#login-modal').modal("hide");
        cs('#signup-modal').modal({
            toggle:true,
            focusOn: "input:first",
            width: "510px",
            height: "auto",
            backdrop: "static"

        });
    }

    // -----------------------------------------------------------

    var carousel = cs('#slider-home #massdata-slides'),
        thumbs = cs('#slider-home #massdata-slides-thumbnail');

    if(carousel.length > 0 || thumbs.length > 0){
        carousel.carouFredSel({
            responsive: false,
            infinite: true,
            items: {
                start: 0,
                visible: 1,
                width: 928,
                height: 433
            },
            scroll: {
                items:1,
                fx: 'crossfade',
                pauseOnHover : true,
                onBefore: function( data ) {
                    var id_name = data.items.visible.attr("id");
                    id_name = id_name.split( 'main-' ).join( 'thumb-' );
                    thumbs.find( 'img' ).removeClass( 'selected' );
                    thumbs.find( 'img#'+id_name ).addClass( 'selected' );
                    thumbs.trigger( 'slideTo', ["img#"+id_name] );
                }
            }
        }, {
            wrapper: {
                element:"div",
                classname:"massdata-slides-wrapper"
            }
        });

        thumbs.carouFredSel({
            infinite: true,
            width: '100%',
            auto: false,
            height: 95,
            items: {
                start: 0,
                visible: 10
            },
            scroll:{
                items:1,
                fx: 'scroll',
                pauseOnHover : true
            }
        });

        thumbs.find("img").on("click" , function() {
            var id_name = cs(this).attr( 'id' );
            id_name = id_name.split( 'thumb-' ).join( 'main-' );
            carousel.trigger("finish").trigger('slideTo', ["img#"+id_name , {
                scroll:{
                    items:5,
                }
            }]);
        });

        cs('#slider-home').prepend('<span class="slider-pointer"></span>');
    }

    if(cs(".event-thumbnail").length > 0){
        cs(".event-thumbnail").carouFredSel({
            circular    : true,
            infinite    : true,
            responsive  : false,
            direction   : "left",
            width       : 690,
            height      : 110,
            align       : "left",
            padding     : 0,
            items       : {
                visible : 5,
                minimum : 3,
                start   : 0,
                width   : 132,
                height  : 102
            },
            scroll      : {
                items   : 1,
                fx      : "scroll",
                easing  : "easeOutSine",
                duration: 500,
                pauseOnHover: false
            },
            auto        : {
                play    : true,
                duration: 1000
            },
            prev        : {
                button  : function(){
                    var prev = cs(this).parents("td").find("div.event-buttons a.prev");
                    return prev;
                },
                duration: 300
            },
            next        : {
                button  : function(){
                    var next = cs(this).parents("td").find("div.event-buttons a.next");
                    return next;
                },
                duration: 300
            }
        });
    }

    // -----------------------------------------------------------

    var thumb_a = cs("#product-spec a[rel^='prettyPhoto']"),
        main_image_a = cs("#product-spec a.woocommerce-main-image"),
        image_swap = true;

    thumb_a.finish().on('click', function(e){
        e.preventDefault();

        if(image_swap)
        {
            image_swap = false;

            var
                thumb_a_href = cs(this).attr("href"),
                thumb_a_title = cs(this).attr("title");
                new_main_image = cs('<img width="593" height="386" src="'+thumb_a_href+'" class="attachment-shop_single wp-post-image" alt="'+thumb_a_title+'" title="'+thumb_a_title+'">').appendTo(main_image_a).fadeOut(0);

            main_image_a.find("img:first").finish().fadeOut({
                duration:300,
                easing:"easeOutSine",
                complete: function(){
                    cs(this).remove();
                    image_swap = true;
                }
            });

            new_main_image.finish().fadeIn({
                duration:300,
                easing:"easeOutSine"
            });
        }

    });

    // -----------------------------------------------------------

    if(cs("ul#item-categories-menu").length > 0 && cs("#custom_list_five").length <= 0)
    {
        var
            additional_li = "",
            multiple = 4,
            count_li = cs("ul#item-categories-menu li").length,
            approx_multiple_count = Math.floor(count_li/multiple),
            closest_multiple = approx_multiple_count*multiple,
            extra_add_li = multiple-(count_li-closest_multiple);

            if(extra_add_li != 4)
            {
                for (var i=0; i<extra_add_li; i++) {
                    additional_li = additional_li+'<li><a></a></li>';
                };

                cs(additional_li).appendTo("ul#item-categories-menu");
            }
    }

    // -----------------------------------------------------------

    if(cs("div.view-charges").length > 0){
        var
            view_charges = cs("div.view-charges"),
            first_lightbox_link = view_charges.find("a.view-charges-img:first"),
            first_href = first_lightbox_link.attr("href"),
            first_title = first_lightbox_link.attr("title"),
            first_rel = first_lightbox_link.attr("rel");


            view_charges.find("a.view-charges").attr("href", first_href).attr("title", first_title).attr("rel", first_rel);
            first_lightbox_link.remove();
    }

    // -----------------------------------------------------------

    if(cs("p.search-result").length > 0){
        var
            breadcrumb_trail = cs("h1.title-bar").find("span.trail-end");
            no_result_msg = cs("p.search-result").text();

            breadcrumb_trail.text(no_result_msg);

            cs("p.search-result").remove();
    }

    // -----------------------------------------------------------

    if(cs("ul#stock-item").length > 0){
        var
            highest_li = 0,
            stock_li = cs("ul#stock-item li");
        stock_li.each(function(i, elem){
            if( highest_li < cs(this).outerHeight() )
            {
                highest_li = cs(this).outerHeight();
            }
        });

        stock_li.each(function(i, elem){
           cs(this).css({'height':highest_li+'px'});
        });

    }

    /*
    // -----------------------------------------------------------

        var
            form_request_quote = cs("#tab-request_quote form"),
            form_request_quote_submit = form_request_quote.find("input[type='submit']"),
            form_request_quote_reset = form_request_quote.find("input[type='reset']"),
            invalidFields = cs("pre.invalid-fields");

        if(form_request_quote.length > 0){

            cs.validator.addMethod( "valueNotEqual", function( value, element, param ) {
                //console.log( "element: " + cs(element).attr("id") );
                //console.log( "value: " + value );
                //console.log( "param: " + param );
                return param != value;

            }, "Please select from the dropdown.");

            function numberOfColor( item, element ) {

                var itemName = cs('select[name="'+item+'"]');
                //console.log( itemName.attr('name') );

                if ( itemName.length > 0 ) {
                    if ( itemName.val() == "select"){
                        //console.log( itemName.val() );
                        //console.log("baba2");
                        return true;
                    }
                    else if( itemName.val() == "none" ){
                        //console.log("baba3");
                        return true;
                    }
                    else {
                        //console.log("baba4");
                        cs.validator.format( "How many colors?" )
                        return false;
                    }
                }
            }

            var this_form_request_quote = form_request_quote.validate({
                debug:true,
                ignore: ":hidden:not(.selectpicker)",
                showErrors: function(errorMap, errorList) {
                    var focussed = cs(document.activeElement);

                    console.log(focussed);

                    cs("pre.invalid-fields").html("<div>"+this.numberOfInvalids() + " errors, see highlighted details below."+"</div>");
                    totalInvalidFields(this.numberOfInvalids());

                    this.defaultShowErrors(); //call the default error handlers together with my custom handler

                    cs.each(errorList, function(index, error){
                        var
                            referedElementId = cs(error.element).attr("id"),
                            referedElementText = cs("label[for="+referedElementId+"]").text();

                            errorPopover = cs(error.element).parent("div.form-group").find("a.error-popover");

                            if(errorPopover.length > 0){
                                errorPopover.remove();
                            }

                            cs(error.element).parent("div.form-group").append('<a class="error-popover"><a>');
                            cs("a.error-popover").popover(
                            {
                                trigger: "manual",
                                html: true,
                                placement: "top",
                                container: "label",
                                title : referedElementText,
                                content: error.message
                            });
                            cs("a.error-popover").popover("show");
                    });

                },
                highlight: function(element, errorClass) {
                    cs(element).addClass("highlight-invalid");
                },
                unhighlight: function(element, errorClass) {
                    cs(element).removeClass("highlight-invalid");
                },
                errorPlacement: function(error, element) {
                    //not going to output/append the error, instead going to use showErrors to handle this
                },

                rules:{
                    "md-in-quantity":{
                        required:true,
                        digits:true,
                        maxlength:10
                    },
                    "md-in-capacity":{
                        required:true,
                        valueNotEqual: "select"
                    },
                    "md-in-product-color":{

                    },
                    "md-in-logo-front":{
                        required:true,
                        valueNotEqual: "select",
                    },
                    "md-in-logo-front-color":{
                        required: function(  element ){
                            numberOfColor( 'md-in-logo-front', element );
                        },
                        digits:true
                    },
                    "md-in-logo-back":{
                        required:true,
                        valueNotEqual: "select",
                    },
                    "md-in-logo-back-color":{
                        required: function(  element ){
                            numberOfColor( 'md-in-logo-back', element );
                        },
                        digits:true
                    },
                    "md-in-artwork":{
                        required:true
                    },
                    "md-in-accessories-type":{
                        required:true,
                        valueNotEqual: "select"
                    },
                    "md-in-packaging-type":{
                        required:true,
                        valueNotEqual: "select"
                    },
                    "md-in-print-packaging":{
                        required:true,
                        valueNotEqual: "select",
                    },
                    "md-in-print-packaging-color":{
                        required: function(  element ){
                            numberOfColor( 'md-in-print-packaging', element );
                        },
                        digits:true
                    },
                    email:{
                        required:true,
                        email:true
                    },
                    message:{
                        required:true,
                        maxlength:500
                    },
                    recaptcha_response_field:{
                        required:true
                    }
                }
                //,
                // messages:{
                //     name:{
                //         required:"Please enter a name.",
                //         minlength:"Name minimum 3 letters."
                //     },
                //     phone:{
                //         required:"Please enter a contact number.",
                //         digits:"Only digits allowed.",
                //         maxlength:"Phone number incorrect length."
                //     },
                //     email:{
                //         required:"Please provide an email.",
                //         email:"Please provide a valid email."
                //     },
                //     message:{
                //         required:"Please provide your feedback.",
                //         maxlength:"Message exceeds 300 character."
                //     },
                //     recaptcha_response_field:{
                //         required:"Please enter captcha."
                //     }
                // },
                // errorPlacement: function(error, element) {
                //     error.prependTo( element.parent() )
                // }
            });

            form_request_quote_reset.click(function(){
                this_form_request_quote.resetForm();
                invalidFields.empty().hide();
                form_request_quote.find('.selectpicker').selectpicker('refresh');
                //clear_error_msg();
            });

            function totalInvalidFields(i) {

                if( i > 0 ) {
                    if( invalidFields.find("div").length > 0 ) {
                        invalidFields.show();
                    }
                }
                else{
                    invalidFields.hide();
                }

            } totalInvalidFields(0);

        }
    */

    cs(window).load(function(){

        // code goes here when window has finish loading

    });

});