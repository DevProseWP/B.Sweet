jQuery(function($) {
    $('#minmax').html("Choose between " + minimum_items + " - " + maximum_items + " products");
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
                      $('.progress-indicator').html('This basket is already filled to capacity.').css('color', 'white');
                    } else {
                      $('.progress-bar-advancer').css('width', percent+'%');
                      $('.progress-indicator').html('Add up to <strong>'+(maximum_items - items)+'</strong> more products!').css('color', 'white');
                    }
                } else {
                 $('.progress-bar-advancer').css('width', percent+'%');
                 $('.progress-indicator').html('Add Some Products!').css('color', '#333');
                }
        
        } else {
            console.log(items);
            console.log((items) / maximum_items);
            var percent = (Math.floor(((items) / maximum_items)*100));
            if (percent == 100) {
                      $('.progress-bar-advancer').css('width', percent+'%');
                      $('.progress-indicator').html('This basket is already filled to capacity.').css('color', 'white');
                    } else {
            $('.progress-bar-advancer').css('width', percent+'%');
            $('.progress-indicator').html('Add up to <strong>'+(maximum_items - items)+'</strong> more products!').css('color', 'white');
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
            fireError("Not Enough Room For This Product.");
            event.stopImmediatePropagation();
        } else if ((curr_quantity) == maximum_items) {
            console.log(curr_quantity+" "+maximum_items);
            fireError("You can't have more than " + maximum_items +
                " items in your basket.");
            event.stopImmediatePropagation();
        }  else if (products_size > max_size) {
            fireError("Item is too big for this basket.");
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

         console.log(items_in_cart+" "+maximum_items);

        if (($(this).hasClass('product_type_variable'))&& (items_in_cart < maximum_items)){
            event.stopImmediatePropagation();
            window.location = $(this).attr('href');
        }

        if (((items_in_cart + item_size) > maximum_items) && (items_in_cart < maximum_items)) {
            fireError("This Product is too big for this basket");
            event.stopImmediatePropagation();
        } else if ((items_in_cart) == maximum_items) {
            fireError("You can't have more than " + maximum_items +
                " items in your basket.");
            event.stopImmediatePropagation();
        }  else if (item_size > max_size) {
           fireError("Item is too big for this basket.");
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
            fireError("You must have at least <strong>"+minimum_items+"</strong> in your basket.  Add some more!");
            event.preventDefault();
            return false;
        } else {
            return true;
        }
    });
   
   $(document).ready(function() {
        updateProgress(); 
   });

});
