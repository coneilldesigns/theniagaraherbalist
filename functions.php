<?php

function dev($value) {
	echo '<pre>';
	var_dump($value);
	echo '###### DEBUG ######';
	echo '</pre>';
	die();
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}


include_once('imageResize.php');

add_action( 'greenline_menu_fetch', 'greenline_menu_fetch_func' );

function my_array_unique($array, $keep_key_assoc = false){
    $duplicate_keys = array();
    $tmp = array();

    foreach ($array as $key => $val){
        // convert objects to arrays, in_array() does not support objects
        if (is_object($val))
            $val = (array)$val;

        if (!in_array($val, $tmp))
            $tmp[] = $val;
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key)
        unset($array[$key]);

    return $keep_key_assoc ? $array : array_values($array);
}

function greenline_menu_fetch_func() {

	$savedfile = dirname(__FILE__) . '/menu_json.json';
	$testfile = dirname(__FILE__) . '/menu_json_test.json'; // For Development
  $requestInformation = dirname(__FILE__) . '/menu_request_info.txt'; // For Development
	$imagesurls = dirname(__FILE__) . '/imageurls.txt';

	$url_base = 'https://api.getgreenline.co/api/v1/external/company/664/location/665/posListings';

  $api_key_1 = '084a7b5e-7fc9-4e5a-a409-a26b3b0b0862';
  $api_key_2 = '289bd43b-d33f-47e6-a171-12a98a41ed57';

	$User_Agent = 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31';
	$request_headers = array();
  $request_headers[] = 'api-key: ' . $api_key_1;
	$request_headers[] = 'external-api-auth-token: ' . $api_key_2;
	$request_headers[] = 'Accept: application/json';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_base);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Result of the API call
	$result = curl_exec($ch);
	curl_close($ch);

  file_put_contents($requestInformation, "");
  file_put_contents($requestInformation, print_r($result, true), FILE_APPEND);

	//Decode the call
	$result = json_decode($result, true);

	// Seperate
	$categories = $result['categories'];
	$allProducts = $result['products'];

	// Go through all product variants and make new array
	$allInStockVariants = array();

	foreach($allProducts as $key => $value) {
		if (!empty($allProducts[$key]['variants'])) {
			foreach($allProducts[$key]['variants'] as $key2 => $value2) {
				if($allProducts[$key]['variants'][$key2]['inStock']) {
					$allInStockVariants[] = $allProducts[$key]['variants'][$key2];
				} else {}
			}
		}
	}

	// Go through all products, get rid of variants and keep instock parents
	$allInStockParentProducts = $allProducts;

	foreach($allInStockParentProducts as $key => $value) {
		unset($allInStockParentProducts[$key]['variants']);
		if($allInStockParentProducts[$key]['inStock']) {} else {
			unset($allInStockParentProducts[$key]);
		}
	}

	// Reset array numbers (key)
	$allInStockParentProducts = array_values($allInStockParentProducts);

	// Put arrays together
	$allInStockProductsBulk = array_merge($allInStockVariants, $allInStockParentProducts);

	// Go through array objects and remove duplicates, reset numbers for good measure
	$allInStockProducts = my_array_unique($allInStockProductsBulk);
	$allInStockProducts = array_values($allInStockProducts);


  // Prepare Download Directory
  $templateDirectory = get_template_directory_uri();
  $files = glob($templateDirectory . '/assets/img/menu/*'); // get all file names
  foreach($files as $file){ // iterate files
    if(is_file($file))
      unlink($file); // delete file
  }
	// Download the images for all instock products
	$inStockProductImages = array();
	foreach ($allInStockProducts as $product) {

	  $inStockProductImages[] = $product['imageUrl'];
    // console_log($inStockProductImages);
    $pathinfo = pathinfo($product['imageUrl']);
	  $imagebasename = $pathinfo['basename'];

	  $content = curl_init($product['imageUrl']);
	  $filename = dirname(__FILE__) . "/assets/img/menu/" . $imagebasename;

		if(file_exists($filename)){
	      ob_flush();
	      curl_close($content);
	  } else {
	    $fp = fopen($filename, "wb");
	    curl_setopt($content, CURLOPT_FILE, $fp);
	    curl_setopt($content, CURLOPT_HEADER, 0);
	    curl_exec($content);
	    fwrite($fp, $content);
	    ob_flush();
	    curl_close($content);
	    resizeImage($filename,$filename,1024,1024,100);
	  }
	}
	ob_end_flush();
	fclose($fp);

	// Go through array and update URLs to point to current domain, if theres not image available, add placeholder image
	foreach($allInStockProducts as $key => $product){
		$templateDirectory = get_template_directory_uri();
		if(empty($allInStockProducts[$key]['imageUrl'])) {
		  $allInStockProducts[$key]['imageUrl'] = $templateDirectory . '/assets/img/placeholder.jpg';
		} else {
		  $pathinfo = pathinfo($allInStockProducts[$key]['imageUrl']);
		  $imagePath = $pathinfo['dirname'];
		  $allInStockProducts[$key]['imageUrl'] = str_replace($imagePath, $templateDirectory . '/assets/img/menu', $allInStockProducts[$key]['imageUrl']);
		}
	}

	// Combine arrays after processing the products
	 $parsedCatsAndProds = array(
	 	'categories' => $categories,
	 	'products' => $allInStockProducts
	);

	//Encode as json and get ready to write to file
	$parsedCatsAndProds = json_encode($parsedCatsAndProds);
	$parsedCatsAndProdsDecode = json_decode($parsedCatsAndProds);

	// If the inital request is successful, output parsed json to file

	if($result) {
		file_put_contents($savedfile, "");
    if(file_put_contents($savedfile, print_r($parsedCatsAndProds, true), FILE_APPEND)) {
      wp_mail( 'coneill.designs@gmail.com', 'Automatic email', "Saved JSON fetched from “{$url_base}” as “{$savedfile}” - {$result}.");
    } else {
      wp_mail( 'coneill.designs@gmail.com', 'Automatic email', "Unable to save JSON to “{$savedfile}” - {$result}.");
    }
		file_put_contents($testfile, "");
		if(file_put_contents($testfile, print_r($parsedCatsAndProdsDecode, true), FILE_APPEND)) {
      //wp_mail( 'coneill.designs@gmail.com', 'Automatic email', "Saved JSON fetched from “{$url_base}” as “{$savedfile}” - {$result}.");
    } else {
      //wp_mail( 'coneill.designs@gmail.com', 'Automatic email', "Unable to save JSON to “{$savedfile}” - {$result}.");
    }
	} else {
			wp_mail( 'coneill.designs@gmail.com', 'Automatic email', "Unable to fetch JSON from “{$url_base}” - {$result}.");
	}
}

function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 150,
		'single_image_width'    => 300,

        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
	) );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );


// define the woocommerce_after_shop_loop_item callback

if(!function_exists('ins_woo_product_attr')) {
  function ins_woo_product_attr() {
    global $product;
    if( is_home() || is_shop() || is_product_category() || is_product_tag() ) {
      $product = wc_get_product();
      $str = '<div class="attr-holder">';
        if ($product->get_attribute( 'pa_vendor' )) {
          $str .= '<h6>' . $product->get_attribute( 'pa_vendor' ) . '</h6>';
        }
        if ($product->get_attribute( 'pa_thc' )) {
        $str .= '<table>';
          $str .= '<tr>';
            $str .= '<td>';
            if ($product->get_attribute( 'pa_thc' )) {
              $str .= '<h5>THC: <span>' . $product->get_attribute( 'pa_thc' ) . '</span></h5>';
            }
            $str .= '</td>';
            $str .= '<td>';
            if ($product->get_attribute( 'pa_cbd' )) {
              $str .= '<h5>CBD: <span>' . $product->get_attribute( 'pa_cbd' ) . '</span></h5>';
            }
            $str .= '</td>';
          $str .= '<tr>';
        $str .= '</table>';
      }
      $str .= '</div>';
      echo $str;
    }
  };
}

if(!function_exists('ins_woo_product_page_attr')) {
  function ins_woo_product_page_attr() {
    global $product;
    $attributes = $product->get_attributes();
    if ( ! $attributes ) {
        return;
    }

    $display_result = '';

    foreach ( $attributes as $attribute ) {


        if ( $attribute->get_variation() ) {
            continue;
        }
        $name = $attribute->get_name();
        if ( $attribute->is_taxonomy() ) {

            $terms = wp_get_post_terms( $product->get_id(), $name, 'all' );

            $cwtax = $terms[0]->taxonomy;

            $cw_object_taxonomy = get_taxonomy($cwtax);

            if ( isset ($cw_object_taxonomy->labels->singular_name) ) {
                $tax_label = $cw_object_taxonomy->labels->singular_name;
            } elseif ( isset( $cw_object_taxonomy->label ) ) {
                $tax_label = $cw_object_taxonomy->label;
                if ( 0 === strpos( $tax_label, 'Product ' ) ) {
                    $tax_label = substr( $tax_label, 8 );
                }
            }
            $display_result .= '<span>' . $tax_label . ': ';
            $tax_terms = array();
            foreach ( $terms as $term ) {
                $single_term = esc_html( $term->name );
                array_push( $tax_terms, $single_term );
            }
            $display_result .= '<span>' . implode(', ', $tax_terms) .  '</span></span>';

        } else {
            $display_result .= $name . ': ';
            $display_result .= esc_html( implode( ', ', $attribute->get_options() ) ) . '<br />';
        }
    }
    echo '<div class="attr">' . $display_result . '</div>';
  };
}

// add woocommerce actions
add_action( 'woocommerce_shop_loop_item_title', 'ins_woo_product_attr', 99 );
add_action( 'woocommerce_single_product_summary', 'ins_woo_product_page_attr', 99 );


/* Woo Sidebar Support */
add_post_type_support( 'post_type', 'woosidebars' );

/**
 * Change number or products per row to 3
 */
add_filter('loop_shop_columns', 'loop_columns', 999);
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 3 products per row
	}
}



/*
Advanced Custom Fields Options Page
*/
if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Slideshow',
		'menu_title'	=> 'Slideshow',
		'parent_slug'	=> 'theme-general-settings',
	));
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Social Media',
		'menu_title'	=> 'Social Media',
		'parent_slug'	=> 'theme-general-settings',
	));
  acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Info',
		'menu_title'	=> 'Contact Info',
		'parent_slug'	=> 'theme-general-settings',
	));
}

//Page Slug Body Class
add_filter( 'body_class', 'my_class_names' );
function my_class_names( $classes )
{
	global $post;

	// add 'post_name' to the $classes array
	$classes[] = $post->post_name;
	// return the $classes array
	return $classes;
}

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

show_admin_bar( false );

//require_once('wp_bootstrap-navwalker.php');
require_once('wp_bootstrap-navwalker-mega.php');

function html5_default_scripts() {

    wp_enqueue_style('main-style', get_stylesheet_uri());
    wp_enqueue_style('min-style', get_template_directory_uri() . '/assets/css/landing-page.min.css' );
		wp_enqueue_style('tables', get_template_directory_uri() . '/assets/css/dataTables.bootstrap4.min.css' );
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css' );
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/vendor/font-awesome/all.min.css' );
		wp_enqueue_style('swipebox', get_template_directory_uri() . '/assets/css/swipebox.min.css' );
		// wp_enqueue_style('perfect-scrollbar', get_template_directory_uri() . '/assets/css/perfect-scrollbar.css' );
    wp_enqueue_style('min-style', get_template_directory_uri() . '/assets/css/landing-page.min.css', array(), rand(111,9999), 'all' );
    wp_enqueue_style( 'google-font', 'https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic' );
    wp_enqueue_style( 'google-font-raleway', 'https://fonts.googleapis.com/css?family=Raleway:400,500i,700&display=swap' );


		wp_deregister_script('jquery');
   	wp_register_script('jquery', "https://code.jquery.com/jquery-3.4.1.min.js", false, null);
   	wp_enqueue_script('jquery');
    wp_enqueue_script('vendor-js', get_template_directory_uri() . '/assets/js/vendor.min.js' );
		wp_enqueue_script('lazy', get_template_directory_uri() . '/assets/js/jquery.lazy.min.js' );
		wp_enqueue_script('unveil', get_template_directory_uri() . '/assets/js/jquery.unveil.js' );
		wp_enqueue_script('dataTables', get_template_directory_uri() . '/assets/js/jquery.dataTables.min.js' );
		wp_enqueue_script('data-strap', get_template_directory_uri() . '/assets/js/dataTables.bootstrap4.min.js' );
		wp_enqueue_script('swipebox', get_template_directory_uri() . '/assets/js/jquery.swipebox.min.js' );
		// wp_enqueue_script('perfect-scrollbar', get_template_directory_uri() . '/assets/js/perfect-scrollbar.min.js' );
		wp_enqueue_script('custom-js', get_template_directory_uri() . '/assets/js/custom.min.js' );

    if ( is_page( 'Menu' ) ) {
      wp_register_script('tables-js', get_template_directory_uri() . '/assets/js/tables.js' );
      wp_enqueue_script('tables-js' );
      $translation_array = array( 'templateUrl' => get_stylesheet_directory_uri() );
      wp_localize_script( 'tables-js', 'page_data', $translation_array );
    }

    if ( is_page( 'Menu iPad' ) ) {
      wp_register_script('tables-js-ipad', get_template_directory_uri() . '/assets/js/tables-ipad.js' );
      wp_enqueue_script('tables-js-ipad' );
      $translation_array = array( 'templateUrl' => get_stylesheet_directory_uri() );
      wp_localize_script( 'tables-js-ipad', 'page_data', $translation_array );
    }

}
add_action( 'wp_enqueue_scripts', 'html5_default_scripts' );

/*
This function adds Theme support for featured image
*/
add_theme_support( 'post-thumbnails', array( 'post', 'page', 'slides', 'events' ) );

/*
This function adds custom footer in admin section
*/
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');

function widget_func() {
	echo '<div id="community-events" class="community-events" aria-hidden="false">

		<ul class=" activity-block last" aria-hidden="false">

			<li class="event event-meetup wp-clearfix">
				<div class="event-info">
					<div class="dashicons event-icon" aria-hidden="true"></div>
					<div class="event-info-inner">
						<p style="padding:0; margin:0;" class="event-title">Update Current Inventory</p>
						<span class="event-city">This will rerun the product inventory lookup and save</span>
					</div>
				</div>

				<div class="event-date-time">
					<span class="event-date"><a id="resync" class="event-title" href="#">Re-Sync</a></span>
						<span class="event-time">7:00 pm</span>
				</div>
			</li>

			<li class="event event-meetup wp-clearfix">
				<div class="event-info">
					<div class="dashicons event-icon" aria-hidden="true"></div>
					<div class="event-info-inner">
						<p style="padding:0; margin:0;" class="event-title">Update Inventory Images</p>
						<span class="event-city">This will re-download all images from greenline server to fix broken links</span>
					</div>
				</div>

				<div class="event-date-time">
					<span class="event-date"><a class="event-title" href="https://www.meetup.com/Buffalo-Wordpress/events/262977405/">Re-Sync</a></span>

						<span class="event-time">7:00 pm</span>

				</div>
			</li>

	</ul>
	</div>';
}




add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

function my_action_javascript() { ?>

	<script type="text/javascript" >
	jQuery(document).ready(function($) {

		$( "#resync" ).on( "click", function() {
			console.log('function sent');
			var data = {
				'action': 'greenline_menu_fetch_func'
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				alert('Got this from the server: ' + response);
			});
		});

	});
	</script>

<?php }

function my_custom_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Greenline Sync', 'widget_func');
}

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    #custom_help_widget .inside {
			padding:0px;
			margin:0;
		}
		#custom_help_widget #community-events {
			margin-bottom:0;
		}
  </style>';
}

/*
This function adds custom footer in admin section
*/
function remove_footer_admin () {
echo 'Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | Designed & Developed by <a href="http://www.destroyproductions.ca/portfolio" target="_blank">coDESIGN</a></p>';
}

add_filter('admin_footer_text', 'remove_footer_admin');

// Limit except length to 125 characters.
// tn limited excerpt length by number of characters
function get_excerpt( $post, $count ) {
  $permalink = get_permalink($post);
  $excerpt = get_the_excerpt();
  $excerpt = strip_tags($excerpt);
  $excerpt = substr($excerpt, 0, $count);
  $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
  $excerpt = '<p>'.$excerpt.'...</p>';
  return $excerpt;
}


function wpbeginner_remove_version() {
return '';
}
add_filter('the_generator', 'wpbeginner_remove_version');


/*
This function adds google analytics to the footer
*/
add_action('wp_footer', 'add_googleanalytics');
function add_googleanalytics() {
// Paste your Google Analytics code from Step 6 here
 }

/*
This function adds menus tab to WP admin section
*/
function register_my_menus() {
	register_nav_menus( array(
		'mega_menu'   => __( 'Mega Menu', 'theniagaraherbalist-full' ),
		'extra_menu'   => __( 'Extra Menu', 'theniagaraherbalist-full' )
	) );
}
add_action( 'init', 'register_my_menus' );

add_theme_support('woocommerce');
add_filter( 'woocommerce_is_purchasable', '__return_false'); // DISABLING PURCHASE FUNCTIONALITY AND REMOVING ADD TO CART BUTTON FROM NORMAL PRODUCTS
remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10); // REMOVING PRICE FROM VARIATIONS
remove_action('woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20); // REMOVING ADD TO CART BUTTON FROM VARIATIONS


function register_my_widgets() {

		//register MegaMenu widget if the Mega Menu is set as the menu location
		$location = 'mega_menu';
		$css_class = 'mega-menu-parent';
		$locations = get_nav_menu_locations();
		if ( isset( $locations[ $location ] ) ) {
		  $menu = get_term( $locations[ $location ], 'nav_menu' );
		  if ( $items = wp_get_nav_menu_items( $menu->name ) ) {
		    foreach ( $items as $item ) {
		      if ( in_array( $css_class, $item->classes ) ) {
		        register_sidebar( array(
		          'id'   => 'mega-menu-item-' . $item->ID,
		          'description' => 'Mega Menu items',
		          'name' => $item->title . ' - Mega Menu',
		          'before_widget' => '<li id="%1$s" class="mega-menu-item">',
		          'after_widget' => '</li>',

		        ));
		      }
		    }
		  }
		}

    register_sidebar( array(
        'name' => __( 'Info Sidebar', 'wpb' ),
        'id' => 'info-sidebar',
        'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Bottom Header Sidebar', 'wpb' ),
        'id' => 'bottom-header-sidebar',
        'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="col-lg-3 col-md-4 widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Footer Sidebar', 'wpb' ),
        'id' => 'footer-sidebar',
        'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="col-lg-3 col-md-3 widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Instagram Sidebar', 'wpb' ),
        'id' => 'instagram-sidebar',
        'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="col-lg-2 widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Woocommerce Sidebar', 'wpb' ),
        'id' => 'woocommerce-sidebar',
        'description' => __( 'The main sidebar appears on the right on each page except the front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title"><span class="widget-icon"><span></span></span>',
        'after_title' => '</h3>',
    ) );

}

add_action( 'widgets_init', 'register_my_widgets' );

/* Thumbnails */
add_image_size( 'front-thumb', 200, 200, array( 'center', 'center' ) );


function add_title_attr_to_wc_cat_link( $args ){
    // include new walker class - where ever you saved/named it
    include_once( 'wc-walker-with-title.php' );

    if ( class_exists( 'My_WC_Product_Cat_List_Walker', false ) ) :
        // set the name as the new walker for the widget args
        $args['walker'] = new My_WC_Product_Cat_List_Walker;
    endif;

    return $args;
}

add_filter('WC_Product_Cat_List_Walker', 'add_title_attr_to_wc_cat_link');

/*
	This function will return an atahced image per post.

	If there is no attached image for a post a generic image will be returned
*/
function get_post_image($postId,$Wdt,$Hgt,$outputResults = true)
{
	/*
		we give user the option to manually select a thumbnail to attach to this post
		The value must be http://www.domain.com/wp-content/uploads/..../image.[extension]
	*/
	$thumbnail = get_post_meta($postId,'thumbnail',true);

	//
	if( $thumbnail )
	{
		$image_src = $thumbnail;
	}
	else
	{
		$arrImages =& get_children('post_type=attachment&post_mime_type=image&post_parent=' . $postId );

		if( $arrImages )
		{
			$arrKeys = array_keys($arrImages);
			$num_elements = count( $arrKeys);
			$last_element = $arrKeys[$num_elements-1];
			// This is the full location to the image
			$sImageUrl = wp_get_attachment_url($last_element);
			// The URL to the homepage
			$place= get_bloginfo('url');

			$output = str_replace($place, "", $sImageUrl);

			$name = get_bloginfo('template_url');

			$image_src = $name .'/scripts/timthumb.php?src=' . $output . '&amp;w=' . $Wdt . '&amp;h=' . $Hgt . '&amp;zc=1&amp;q=100';
		}
		else
		{
			$image_src = get_bloginfo('template_url') . '/images/noimage.jpg';
		}
	}

	$sImgString = '<img src="' . $image_src . '" width="' . $Wdt . '" height="' . $Hgt . '" />';

	if( $outputResults )
		echo $sImgString;
	else
		return $sImgString;
}

/*
	This will make sure that the provided string is no longer than the desired length.

	However, if it is then it will append '. . .' to the end of the string
*/
function verifyStringLength($string,$characters)
{
	if( strlen($string) > $characters )
	{
		$string = substr($string,0,$characters-5) . '.&nbsp;.&nbsp;.';
	}

	return $string;
}

// Mirror function to verifyStringLength
function verifyLength($string,$characters)
{
	return verifyStringLength($string,$characters);
}

/*
	This will return the content of the page by title.

	Its a shortcut method
*/
function getPageContent($pageTitle)
{
	$page = get_page_by_title($pageTitle);

	if( $page )
	{
		return $page->post_content;
	}
	else
	{
		return null;
	}
}

/*
	This will return the link of the page by title.

	Its a shortcut method
*/
function getPageLink($pageTitle)
{
	$page = get_page_by_title($pageTitle);

	if( $page )
	{
		return get_permalink($page->ID);
	}
	else
	{
		return null;
	}
}

/*
	This will return the agreed upon date in a desired and constrant format
*/
function postDateFormat($postDate,$dateFormat = "M d, Y")
{
	return mysql2date($dateFormat,$postDate);
}

function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg_thumb_display() {
  echo '
    <style>
    td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
      width: 100% !important;
      height: auto !important;
    }
    </style>
  ';
}
add_action('admin_head', 'fix_svg_thumb_display');

?>
