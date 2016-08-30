<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

// This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
// 

define('WP_BBDEBUG',true);

require_once( 'library/bones.php' );

// LOAD MY CUSTOM FUNCTIONS (MTMD functions)
require_once( 'library/myfunctions.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  // require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
// add_image_size( 'bones-thumb-600', 600, 150, true );
// add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        // 'bones-thumb-600' => __('600px by 150px'),
        // 'bones-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/*
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722

  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162

  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');

  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'bones_fonts');


// REMOVE WOOCOMMERCE LIGHTBOX!

function woo_remove_lightboxes() {
  // Styles
  wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
  // Scripts
  wp_dequeue_script( 'prettyPhoto' );
  wp_dequeue_script( 'prettyPhoto-init' );
  wp_dequeue_script( 'fancybox' );
  wp_dequeue_script( 'enable-lightbox' );
}

add_action( 'wp_enqueue_scripts', 'woo_remove_lightboxes', 99 );

function df_woocommerce_single_product_image_html($html) {
    $html = str_replace('data-rel="prettyPhoto', 'rel="lightbox', $html);
    return $html;
}
add_filter('woocommerce_single_product_image_html', 'df_woocommerce_single_product_image_html', 99, 1); // single image
add_filter('woocommerce_single_product_image_thumbnail_html', 'df_woocommerce_single_product_image_html', 99, 1); // thumbnails



// REMOVE WORDPRESS ADMIN BAR LOGO

function annointed_admin_bar_remove() {
        global $wp_admin_bar;

        /* Remove their stuff */
        $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'annointed_admin_bar_remove', 0);







// ADD EXCERPT TO PAGES FOR PAGE DESCRIPTION

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

// REMOVE WOOCOMMERCE REVIEWS

add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab($tabs) {
 unset($tabs['reviews']);
 return $tabs;
}

// ADD WOOCOMMERCE THEME SUPPORT

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

// WOOCOMMERCE CART AJAXIFICATION FOR UPDATE

add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	?>
	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a>
	<?php

	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;
}

/**
 * Redirect users to custom URL based on their role after login
 *
 * @param string $redirect
 * @param object $user
 * @return string
 */
function wc_custom_user_redirect( $redirect, $user ) {
	// Get the first of all the roles assigned to the user
	$role = $user->roles[0];
	$dashboard = admin_url();
	$myaccount = get_permalink( wc_get_page_id( 'myaccount' ) );
	if( $role == 'administrator' ) {
		//Redirect administrators to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'shop-manager' ) {
		//Redirect shop managers to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'editor' ) {
		//Redirect editors to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'author' ) {
		//Redirect authors to the dashboard
		$redirect = $dashboard;
	} elseif ( $role == 'customer' || $role == 'subscriber' ) {
		//Redirect customers and subscribers to the "My Account" page
		$redirect = $myaccount;
	} else {
		//Redirect any other role to the previous visited page or, if not available, to the home
		$redirect = wp_get_referer() ? wp_get_referer() : home_url();
	}
	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );


/*
* wc_remove_related_products
*
* Clear the query arguments for related products so none show.
* Add this code to your theme functions.php file.
*/
function wc_remove_related_products( $args ) {
  return array();
}

add_filter('woocommerce_related_products_args','wc_remove_related_products', 10);




////////////////////////////////////////////////////////////////////////////////////
// Change single page cart text to add and change after added
////////////////////////////////////////////////////////////////////////////////////


/**
 * Change the add to cart text on single product pages
 */
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );

function woo_custom_cart_button_text() {

	global $woocommerce;

	foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];

		if( get_the_ID() == $_product->id ) {
			return __('Add', 'woocommerce'); // Used to be Added
		}
	}

	return __('Add', 'woocommerce');
}

/**
 * Change the add to cart text on choose basket page
 */

 //For single product page

 // Change 'add to cart' text on single product page (only for product ID 1198)
 add_filter( 'woocommerce_product_single_add_to_cart_text', 'byob_basket_id_add_to_cart_text_regular' );
 function byob_basket_id_add_to_cart_text_regular( $default ) {
     if ( get_the_ID() == 1198 ) {
         return __( 'Time to fill your basket', 'your-slug' );
     } else {
         return $default;
     }
 }

 // Change 'add to cart' text on single product page (only for product ID 514)
 add_filter( 'woocommerce_product_single_add_to_cart_text', 'byob_basket_id_add_to_cart_text_jumbo' );
 function byob_basket_id_add_to_cart_text_jumbo( $default ) {
     if ( get_the_ID() == 514 ) {
         return __( 'Time to fill your basket', 'your-slug' );
     } else {
         return $default;
     }
 }


/**
 * Change the add to cart text on product loop
 */

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_loop_custom_cart_button_text' );

function woo_loop_custom_cart_button_text($text ) {

	global $woocommerce;

	foreach($woocommerce->cart->get_cart() as $cart_item_key => $values ) {
		$_product = $values['data'];

		if( get_the_ID() == $_product->id ) {
			return __('Add', 'woocommerce'); // used to be Added
		}
	}

	return __('Add', 'woocommerce');
}

add_filter( 'woocommerce_add_cart_item_data', 'wdm_empty_cart', 10, 3);
function wdm_empty_cart( $cart_item_data, $product_id, $variation_id )
{
  global $woocommerce;

    $this_product = get_product($product_id);
      if ($this_product->is_type('composite')){
         $cart = WC()->cart->get_cart();
         foreach ($cart  as $cart_item_key => $cart_item) {
          $type = $cart_item['data']->product_type;
          $virtual = $cart_item['data']->virtual;
          if(($type == 'composite')||(($virtual == "no") && ($type !== 'bundle'))){
            WC()->cart->remove_cart_item($cart_item_key);
          }
         }
      }

  return $cart_item_data;
  }




// Cart message for choosing basket (jumbo) in byob

add_filter( 'wc_add_to_cart_message', 'byob_custom_add_to_cart_message_jum', 10, 2 );

function byob_custom_add_to_cart_message_jum( $message, $product_id ) {
global $woocommerce;
if ( $product_id == 1198 ) {
$return_to  = "/build/choose-your-products/";
$message    = sprintf('<a href="%s" class="button wc-forwards">%s</a> %s', $return_to, __('Click here to continue <i class="fa fa-arrow-right"></i>', 'woocommerce'), __('Your basket was successfully added, now choose your basket items.', 'woocommerce') );
}
return $message;
}


  // Succesfully added byob product item, return

  add_filter( 'wc_add_to_cart_message', 'byob_product_added_return_custom', 10, 2 );
  function byob_product_added_return_custom( $message, $product_id ) {
  global $woocommerce;

    if( has_term( 'all-product-categories', 'product_cat', $product_id ) ){
    $return_to  = "/build/choose-your-products/";
    $message    = sprintf('%s', __('This item was successfully added', 'woocommerce') );
  }
  return $message;
  }




// Change paypal images

function replacePayPalIcon($iconUrl) {
	return get_bloginfo('stylesheet_directory') . '/library/images/acceptedCards.png';
}

add_filter('woocommerce_paypal_icon', 'replacePayPalIcon');


// Add ajax to single product page

// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_loop_add_to_cart', 30 );


// Load more instead of pagination on composite products
add_filter( 'woocommerce_component_options_paginate_results', 'wc_cp_append_component_options', 10, 3 );
function wc_cp_append_component_options( $paginate, $component_id, $composite ) {
	return false;
}

// Remove Visit Store in admin mb_decode_numericentity
function remove_visit_store_link() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('view-store');        // Remove the view site link
}
add_action( 'wp_before_admin_bar_render', 'remove_visit_store_link' );




// check for empty-cart get param to clear the cart
add_action('init', 'woocommerce_clear_cart_url');
function woocommerce_clear_cart_url() {
	global $woocommerce;
	if( isset($_REQUEST['clear-cart']) ) {
		$woocommerce->cart->empty_cart();
	}
}





// Remove update notification
function remove_core_updates(){
global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');

// Remove certain product types woocommerce
add_filter( 'product_type_selector', 'cartible_product_type_selector', 10, 2 );


/**
 * Remove product types we do not want to be shown.
 */
function cartible_product_type_selector( $product_types ) {


  return $product_types;
}


add_action( 'save_post', 'clear_beaver' );
function clear_beaver() {
  FLBuilderModel::delete_asset_cache_for_all_posts();
}
function my_builder_after_save_layout( $post_id, $publish, $data, $settings ) {
      FLBuilderModel::delete_asset_cache_for_all_posts();
}
add_action( 'fl_builder_before_save_layout', 'my_builder_after_save_layout', 10, 4 );

function extractComposites($items){
  $composites = array(1198);
  foreach($items as $item => $values) {
    if(isset($values['composite_data'])) {
      foreach ($values['composite_data'] as $key => $value) {
        if(!in_array($value['product_id'], $composites)) $composites[] = $value['product_id'];

      }
    }
  }
  return $composites;
}

function getProductsInComposite($items) {
  if($items) {
    if (isset($items[key($items)]['composite_data'][key($items[key($items)]['composite_data'])])) {
      $building_basket = true;
      $basket_id = $items[key($items)]['composite_data'][key($items[key($items)]['composite_data'])]['product_id'];
      $maximum_volume =  (get_field('product_volume', $basket_id)) ?  get_field('product_volume', $basket_id) : 1;
      $maximum_size  =  (get_field('product_size', $basket_id)) ? get_field('product_size', $basket_id) : 1 ;
      $maximum_items =  (get_field('maximum_items', $basket_id)) ? get_field('maximum_items', $basket_id) : 6 ;
      $minimum_items =  (get_field('minimum_items', $basket_id)) ? get_field('minimum_items', $basket_id) : 4;
      $excludes = extractComposites($items);
      $current_quantity = 0;
        foreach($items as $item => $values) {
            if (!in_array($values['data']->id, $excludes)) {
              if(($values['data']->product_type == 'bundle')|| ($values['data']->virtual == "yes")) continue; 
               $prodsize = (get_field('product_size', $basket_id)) ? get_field('product_size', $values['data']->id) : 1 ;
               $current_quantity = $current_quantity + ($values['quantity'] * $prodsize);
            }
        }
    }
  }
  return $current_quantity;

}



add_action( 'wp_ajax_get_cart_count', 'get_cart_count' );
add_action( 'wp_ajax_nopriv_get_cart_count', 'get_cart_count' );

function get_cart_count(){
  global $woocommerce;
  $items = $woocommerce->cart->get_cart();
  echo getProductsInComposite($items);
  die();

}

// Stop anyone else than admin listing admin user

add_action('pre_user_query','yoursite_pre_user_query');
function yoursite_pre_user_query($user_search) {
  $user = wp_get_current_user();
  if ($user->ID!=1) { // Is not administrator, remove administrator
    global $wpdb;
    $user_search->query_where = str_replace('WHERE 1=1',
      "WHERE 1=1 AND {$wpdb->users}.ID<>1",$user_search->query_where);
  }
}







/**
 * Add the Gift Message field to the checkout
 */
add_action( 'woocommerce_after_order_notes', 'gift_message_field' );

function gift_message_field( $checkout ) {

    echo '<div id="gift_message_field">';

    woocommerce_form_field( 'my_field_name', array(
        'type'          => 'textarea',
        'class'         => array('gift_message'),
        'maxlength'		=> '300',
        'label'         => __('Gift Message'),
        'placeholder'   => __('Write your gift message here and we will send it with your basket!'),
        ), $checkout->get_value( 'my_field_name' ));

        ?>
    <script type="text/javascript">
    var order_count_characters = 300;
    jQuery("#my_field_name").after("<p id=\"order_comment_count\" style=\"font-style:italic;\"><small>You have <strong>" + order_count_characters + "</strong> characters remaining</small></p>");
    jQuery("body").on('keyup', '#my_field_name', function(){

        if(jQuery(this).val().length > order_count_characters){
            jQuery(this).val(jQuery(this).val().substr(0, order_count_characters));
        }

        var remaining = order_count_characters - jQuery(this).val().length;

        jQuery('#order_comment_count').html("<small>You have <strong>" +  remaining + "</strong> characters remaining</small>");

        if (remaining <= 10) {
            jQuery("#order_comment_count").css("color", "red");
        } else {
            jQuery("#order_comment_count").css("color", "black");
        }

    });

    </script>
    <?php

    echo '</div>';

}


/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['my_field_name'] ) ) {
        update_post_meta( $order_id, 'Gift Message', sanitize_text_field( $_POST['my_field_name'] ) );
    }
}


/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Gift Message').':</strong> ' . get_post_meta( $order->id, 'Gift Message', true ) . '</p>';
}


// add_filter( 'woocommerce_component_options_per_page', 'wc_cp_component_options_per_page', 10, 3 );
// function wc_cp_component_options_per_page( $results_count, $component_id, $composite ) {
//   if ($component_id == "1463692244"){
//     $results_count = 18;
//   }
//   return $results_count;
// }

add_filter( 'woocommerce_component_options_per_page', 'wc_cp_component_options_per_page', 10, 3 );
function wc_cp_component_options_per_page( $results_count, $component_id, $composite ) {
  $results_count = 50;
  return $results_count;
}


// Sort composite by price
add_filter( 'woocommerce_composite_component_default_orderby', 'wc_cp_sort_by_price', 10, 3 );
function wc_cp_sort_by_price( $default_sort_function, $component_id, $composite ) {
  return 'price';
}


// Modify the default WooCommerce orderby dropdown
function my_woocommerce_catalog_orderby( $orderby ) {
    // unset($orderby["menu_order"]);  //Remove default sorting option.
    // unset($orderby["popularity"]);  //Remove popularity option.
    // unset($orderby["rating"]);      //Remove rating option.
    unset($orderby["date"]);        //Remove newness option.
    // unset($orderby["price"]);       //Remove price: low to high option
    // unset($orderby["price-desc"]);  //Remove price: high to low option
    return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20 );

require_once( 'woo-functions.php' );



/* DON'T DELETE THIS CLOSING TAG */ ?>