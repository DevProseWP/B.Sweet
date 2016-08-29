jQuery(function($) {
    minmax = notification_choose_between.replace('{-#}', minimum_items);
    minmax = minmax.replace('{+#}', maximum_items);
    $('#minmax').html(minmax);
    function fireError(error){
        $('#message-box p').html(error);
        $('#message-box').fadeIn('fast', function() {

        });
    }
    function updateProgress(items) {

        if (items == null) {
           
                items = curr_quantity;
                var percent = (Math.floor(((items) / maximum_items)*100));
                if (percent) {
                    if (percent == 100) {
                      $('.progress-bar-advancer').css('width', percent+'%');
                      $('#progress-bar').html('<a href="/checkout/" class="prog-button button checkout wc-forward"><div class="cell">'+notification_progress_checkout+'</div></a>');
                    } else {
                      $('.progress-bar-advancer').css('width', percent+'%');
                      $('.progress-indicator').html(notification_progress_text.replace('{#}',(maximum_items - items))).css('color', 'white');
                    }
                } else {
                 $('.progress-bar-advancer').css('width', percent+'%');
                 $('.progress-indicator').html(notification_add_products).css('color', '#333');
                }
        
        } else {
            console.log(items);
            console.log((items) / maximum_items);
            var percent = (Math.floor(((items) / maximum_items)*100));
            if (percent == 100) {
                     $('#progress-bar').html('<a href="/checkout/" class="prog-button button checkout wc-forward"><div class="cell">'+notification_progress_checkout+'</div></a>');
                    } else {
            $('.progress-bar-advancer').css('width', percent+'%');
            $('.progress-indicator').html(notification_progress_text.replace('{#}',(maximum_items - items))).css('color', 'white');
            }
        }
  
    }
    function getCartCount(){

        var counts = new Object();
        counts['volume'] = 0;
        counts['items'] = 0; 
        $('.cart_list li').each(function(index, el) {
            if (index > 0 && $(this).data('volume')) {
                counts['volume'] = counts['volume'] +
                    parseInt($(this).data('volume'));
            }
            if (index > 0){
                counts['items'] = counts['items'] + (parseInt($(this).find('.quantity').html()) * $(this).data('size'))   ;
            }
        });
        return counts;

    }

    $(document).on('click', '.single-ajax-add', function(event) {
        event.preventDefault();
        console.log(curr_quantity+" "+maximum_items);
        if ((curr_quantity + products_size) > maximum_items) {
            fireError(notification_no_room);
            event.stopImmediatePropagation();
        } else if ((curr_quantity) == maximum_items) {
            console.log(curr_quantity+" "+maximum_items);
            fireError(notification_basket_full);
            event.stopImmediatePropagation();
        }  else if (products_size > max_size) {
            fireError(notification_too_big);
            event.stopImmediatePropagation();
        } else {
            curr_quantity = curr_quantity + products_size;
            return true;
        }
        return false;
    
    });

    $(document).on('click', '#close-message', function(event) {
        $('#message-box').fadeOut('fast');
    });
    $(document).on('click', '.ajax-flag-quota', function(event) {
        counts = getCartCount();
        var items_in_cart = counts['items'];
        var current_volume = counts['volume'];
        event.preventDefault();
        item_size = $(this).parent('li').data('size');


        if (($(this).hasClass('product_type_variable'))&& (items_in_cart < maximum_items)){
            event.stopImmediatePropagation();
            window.location = $(this).attr('href');
        }

        if (((items_in_cart + item_size) > maximum_items) && (items_in_cart < maximum_items)) {
            fireError(notification_no_room);
            event.stopImmediatePropagation();
        } else if ((items_in_cart) == maximum_items) {
           $('#progress-bar').html('<a href="/checkout/" class="prog-button button checkout wc-forward"><div class="cell">'+notification_progress_checkout+'</div></a>');

            event.stopImmediatePropagation();
        }  else if (item_size > max_size) {
           fireError(notification_too_big);
            event.stopImmediatePropagation();
        }    else {
            items_in_cart = items_in_cart + item_size;
    
            updateProgress(items_in_cart);
            return true;
        }
        return false;
    });
    


    $(document).on('click', '#review-cart a', function(event) {
        
        var counts = getCartCount();
        if (counts['items'] < minimum_items) {
            fireError(notifications_not_enough.replace('{#}',minimum_items));
            event.preventDefault();
            return false;
        } else {
            return true;
        }
    });
   
    $(document).on('click', '.buttons .checkout', function(event) {
        
        var counts = getCartCount();
        if (counts['items'] < minimum_items) {
            fireError(notifications_not_enough.replace('{#}',minimum_items));
            event.preventDefault();
            return false;
        } else {
            return true;
        }
    });


   $(document).ready(function() {
        updateProgress(); 
   });

   $(document).on('change', '.subcat-selector', function(event) {
    event.preventDefault();
    var target = $(this).data('target');
    var request = {
        'action': 'update_sub_cat',
        'showcat': $(this).val(),
        'target': target,
        'max-size': max_size
    };
     $('.group-'+target+' .working').css('display', 'block');
     $('#group-'+target+' ul').css('opacity', '0');

    $.post('/wp-admin/admin-ajax.php ', request, function(data) {
            $('.group-'+target+' .working').css('display', 'none');
            $('#group-'+target).html('').html(data);
                $('#group-'+target+' ul').css('opacity', '1');
      });
   });

});