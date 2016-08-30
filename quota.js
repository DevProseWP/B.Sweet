jQuery(function($) {
    minmax = notification_choose_between.replace('{-#}', minimum_items);
    minmax = minmax.replace('{+#}', maximum_items);
    $('#minmax').html(minmax);
     function fireError(error){
        $('#message-box p').html(error);
        $('#message-box').fadeIn('fast').delay(6000).fadeOut('fast')
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
        counts['items'] = curr_quantity; 
        // $('.cart_list li').each(function(index, el) {
        //     if (index > 0 && $(this).data('volume')) {
        //         counts['volume'] = counts['volume'] +
        //             parseInt($(this).data('volume'));
        //     }
        //     if (index > 0){
        //         counts['items'] = counts['items'] + (parseInt($(this).find('.quantity').html()) * $(this).data('size'))   ;
        //     }
        // });
        console.log(counts);
        return counts;

    }
    $(document).on('click', '.remove', function(event) {
        $this = $(this);
         $this.parent('li').css('opacity', '0');
        event.preventDefault();
        request = {
            'action':'remove_product',
            'key': $(this).data('key')
        };
       $.post('/wp-admin/admin-ajax.php ', request, function(data) {
           console.log( $this.parent('li'));
           curr_quantity = curr_quantity - $this.data('quantity');
           $this.parent('li').fadeOut('fast');
              updateProgress();
        });

 

    });
    $(document).on('click', '.single-ajax-add', function(event) {
        event.preventDefault();

        if ((curr_quantity + products_size) > maximum_items) {
            fireError(notification_no_room);
            event.stopImmediatePropagation();
        } else if ((curr_quantity) == maximum_items) {
        
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

        var current_volume = counts['volume'];
        event.preventDefault();
        item_size = $(this).parent('li').data('size');


        if (($(this).hasClass('product_type_variable'))&& (curr_quantity < maximum_items)){
            event.stopImmediatePropagation();
            window.location = $(this).attr('href');
        }
        console.log(curr_quantity + " " + item_size + " " + maximum_items)
        if (((curr_quantity + item_size) > maximum_items)) {
            console.log('GOTEM!');
            fireError(notification_no_room);
            event.stopImmediatePropagation();
            return false;
        } else if ((curr_quantity) == maximum_items) {
           $('#progress-bar').html('<a href="/checkout/" class="prog-button button checkout wc-forward"><div class="cell">'+notification_progress_checkout+'</div></a>');

            event.stopImmediatePropagation();
            return false;
        }  else if (item_size > max_size) {
           fireError(notification_too_big);
            event.stopImmediatePropagation();
            return false;
        }    else {

            curr_quantity = curr_quantity + item_size;
    
            updateProgress();
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