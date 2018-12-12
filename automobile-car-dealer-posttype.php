<?php
/*
 Plugin Name: Automobile Car Dealer Posttype
 Plugin URI: https://www.buywptemplates.com/
 Description: Creating new post type for Automobile Car Dealer Theme.
 Author: Buy WP Templates
 Version: 1.0
 Author URI: https://www.buywptemplates.com/
*/

 define( 'AUTOMOBILE_CAR_DEALER_POSTTYPE_VERSION', '1.0' );

//custom car type
add_action( 'init','automobile_car_dealer_posttype_create_car_type' );
add_action( 'init', 'automobile_createcar', 0 );
add_action( 'add_meta_boxes', 'automobile_car_dealer_posttype_cs_custom_meta' );
add_action( 'save_post', 'automobile_car_dealer_posttype_bn_meta_save' );
add_action('save_thumbnail','automobile_car_dealer_posttype_bn_save_taxonomy_custom_meta');
add_action( 'init', 'automobile_car_dealer_posttype_create_tag_taxonomies', 0 );

function automobile_car_dealer_posttype_create_car_type() {
  	register_post_type( 'cars',
	    array(
			'labels' => array(
				'name' => __( 'Cars','automobile-car-dealer-posttype' ),
				'singular_name' => __( 'Cars','automobile-car-dealer-posttype' ),
				'add_new_item' =>  __( 'Cars','automobile-car-dealer-posttype' )
			),
			'menu_icon'  => 'dashicons-performance',
			'public' => true,
			'has_archive' => true,
	        'supports' => array(
	        	'title',
	        	'thumbnail',
	        	'revisions',
	        	'editor',
	        )
	    )
	);
	register_post_type( 'testimonials',
		array(
			'labels' => array(
				'name' => __( 'Testimonials','automobile-car-dealer-posttype' ),
				'singular_name' => __( 'Testimonials','automobile-car-dealer-posttype' )
				),
			'capability_type' => 'post',
			'menu_icon'  => 'dashicons-businessman',
			'public' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail'
			)
		)
	);
}

function automobile_createcar() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => __( 'Car Categories', 'automobile-car-dealer-posttype' ),
		'singular_name'     => __( 'Car Categories', 'automobile-car-dealer-posttype' ),
		'search_items'      => __( 'Search Ccats','automobile-car-dealer-posttype' ),
		'all_items'         => __( 'All car Categories','automobile-car-dealer-posttype' ),
		'parent_item'       => __( 'Parent car Category','automobile-car-dealer-posttype' ),
		'parent_item_colon' => __( 'Parent car Category:','automobile-car-dealer-posttype' ),
		'edit_item'         => __( 'Edit car Category','automobile-car-dealer-posttype' ),
		'update_item'       => __( 'Update car Category','automobile-car-dealer-posttype' ),
		'add_new_item'      => __( 'Add New car Category','automobile-car-dealer-posttype' ),
		'new_item_name'     => __( 'New car Category Name','automobile-car-dealer-posttype' ),
		'menu_name'         => __( 'Car Categories','automobile-car-dealer-posttype' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'automobile_createcar' ),
	);

	register_taxonomy( 'automobile_createcar', array( 'cars' ), $args );
}
//create two taxonomies, genres and tags for the post type "tag"
function automobile_car_dealer_posttype_create_tag_taxonomies() 
{
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Tags', 'taxonomy general name' ),
    'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Tags' ),
    'popular_items' => __( 'Popular Tags' ),
    'all_items' => __( 'All Tags' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Tag' ), 
    'update_item' => __( 'Update Tag' ),
    'add_new_item' => __( 'Add New Tag' ),
    'new_item_name' => __( 'New Tag Name' ),
    'separate_items_with_commas' => __( 'Separate tags with commas' ),
    'add_or_remove_items' => __( 'Add or remove tags' ),
    'choose_from_most_used' => __( 'Choose from the most used tags' ),
    'menu_name' => __( 'Tags' ),
  ); 

  register_taxonomy('tag','cars',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'tag' ),
  ));
 }

/* Adds a meta box to the post editing screen */
function automobile_car_dealer_posttype_cs_custom_meta() {
    add_meta_box( 'cs_meta', __( 'Settings', 'automobile-car-dealer-posttype' ),  'automobile_car_dealer_posttype_cs_meta_callback' , 'cars');
}

/**
 * Outputs the content of the meta box
*/
function automobile_car_dealer_posttype_cs_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <table id="postcustomstuff">			
		<tbody id="the-list" data-wp-lists="list:meta">
			<tr id="meta-1">
				<td class="left" id="meta-pricelabel">
					<?php esc_html_e( 'Price', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="minimum_price" id="minimum_price" value="<?php echo $bn_stored_meta['minimum_price'][0]; ?>" />
				</td>
			</tr>
			
			<tr id="meta-2">
				<td class="left" id="meta-compricelable">
					<?php esc_html_e( 'Compare Price', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-comprice" id="meta-comprice" value="<?php echo $bn_stored_meta['meta-comprice'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-3">
				<td class="left">
					<?php esc_html_e( 'Model year', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-modelyear" id="meta-modelyear" value="<?php echo $bn_stored_meta['meta-modelyear'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-4">
				<td class="left" id="meta-mileage">
					<?php esc_html_e( 'Mileage', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-mileage" id="meta-mileage" value="<?php echo $bn_stored_meta['meta-mileage'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-5">
				<td class="left">
					<?php esc_html_e( 'Fuel Type', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-fueltype" id="meta-fueltype" value="<?php echo $bn_stored_meta['meta-fueltype'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-6">
				<td class="left">
					<?php esc_html_e( 'Body Type', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-bodytype" id="meta-bodytype" value="<?php echo $bn_stored_meta['meta-bodytype'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-7">
				<td class="left">
					<?php esc_html_e( 'Color', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-color" id="meta-color" value="<?php echo $bn_stored_meta['meta-color'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-8">
				<td class="left">
					<?php esc_html_e( 'Engine Capacity', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-capacity" id="meta-capacity" value="<?php echo $bn_stored_meta['meta-capacity'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-9">
				<td class="left">
					<?php esc_html_e( 'No of Doors', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-doors" id="meta-doors" value="<?php echo $bn_stored_meta['meta-doors'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-9">
				<td class="left">
					<?php esc_html_e( 'Body Style', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-style" id="meta-style" value="<?php echo $bn_stored_meta['meta-style'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-10">
				<td class="left">
					<?php esc_html_e( 'Address', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-address" id="meta-address" value="<?php echo $bn_stored_meta['meta-address'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-11">
				<td class="left">
					<?php esc_html_e( 'Dealer Email', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-email" id="meta-email" value="<?php echo $bn_stored_meta['meta-email'][0]; ?>" />
				</td>
			</tr>
			<tr id="meta-12">
				<td class="left">
					<?php esc_html_e( 'Dealer Cell Number', 'automobile-car-dealer-posttype' )?>
				</td>
				<td class="left" >
					<input type="text" name="meta-cell" id="meta-cell" value="<?php echo $bn_stored_meta['meta-cell'][0]; ?>" />
				</td>
			</tr>
		</tbody>
	</table>

    <?php
}

/* Saves the custom meta input */
function automobile_car_dealer_posttype_bn_meta_save( $post_id ) {

	if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if( isset( $_POST[ 'minimum_price' ] ) ) {
	    update_post_meta( $post_id, 'minimum_price', sanitize_text_field($_POST[ 'minimum_price' ] ));
	}
	if( isset( $_POST[ 'meta-comprice' ] ) ) {
	    update_post_meta( $post_id, 'meta-comprice', sanitize_text_field($_POST[ 'meta-comprice' ] ));
	}
	if( isset( $_POST[ 'meta-modelyear' ] ) ) {
	    update_post_meta( $post_id, 'meta-modelyear', sanitize_text_field($_POST[ 'meta-modelyear' ] ));
	}
	if( isset( $_POST[ 'meta-mileage' ] ) ) {
	    update_post_meta( $post_id, 'meta-mileage', sanitize_text_field($_POST[ 'meta-mileage' ] ));
	}
	if( isset( $_POST[ 'meta-fueltype' ] ) ) {
	    update_post_meta( $post_id, 'meta-fueltype', sanitize_text_field($_POST[ 'meta-fueltype' ] ));
	}
	if( isset( $_POST[ 'meta-bodytype' ] ) ) {
	    update_post_meta( $post_id, 'meta-bodytype', sanitize_text_field($_POST[ 'meta-bodytype' ] ));
	}
	if( isset( $_POST[ 'meta-color' ] ) ) {
	    update_post_meta( $post_id, 'meta-color', sanitize_text_field($_POST[ 'meta-color' ] ));
	}
	if( isset( $_POST[ 'meta-capacity' ] ) ) {
	    update_post_meta( $post_id, 'meta-capacity', sanitize_text_field($_POST[ 'meta-capacity' ] ));
	}
	if( isset( $_POST[ 'meta-doors' ] ) ) {
	    update_post_meta( $post_id, 'meta-doors', sanitize_text_field($_POST[ 'meta-doors' ] ));
	}
	if( isset( $_POST[ 'meta-style' ] ) ) {
	    update_post_meta( $post_id, 'meta-style', sanitize_text_field($_POST[ 'meta-style' ] ));
	}
	if( isset( $_POST[ 'meta-address' ] ) ) {
	    update_post_meta( $post_id, 'meta-address', sanitize_text_field($_POST[ 'meta-address' ] ));
	}
	if( isset( $_POST[ 'meta-email' ] ) ) {
	    update_post_meta( $post_id, 'meta-email', sanitize_text_field($_POST[ 'meta-email' ] ));
	}
	if( isset( $_POST[ 'meta-cell' ] ) ) {
	    update_post_meta( $post_id, 'meta-cell', sanitize_text_field($_POST[ 'meta-cell' ] ));
	}
}

/* Testimonial section */

/* Adds a meta box to the Testimonial editing screen */
function automobile_car_dealer_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'automobile-car-dealer-posttype-testimonial-meta', __( 'Enter Designation', 'automobile-car-dealer-posttype' ), 'automobile_car_dealer_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'automobile_car_dealer_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function automobile_car_dealer_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'automobile_car_dealer_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
	$desigstory = get_post_meta( $post->ID, 'automobile_car_dealer_posttype_posttype_testimonial_desigstory', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php esc_html_e( 'Designation', 'automobile-car-dealer-posttype' )?>
					</td>
					<td class="left" >
						<input type="text" name="automobile_car_dealer_posttype_posttype_testimonial_desigstory" id="automobile_car_dealer_posttype_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
					</td>
				</tr>
				<tr id="meta-2">
                  	<td class="left">
                    	<?php esc_html_e( 'Facebook Url', 'vw-lawyer-pro-posttype' )?>
                  	</td>
                  	<td class="left" >
                    	<input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_url($bn_stored_meta['meta-facebookurl'][0]); ?>" />
                  	</td>
                </tr>               
                <tr id="meta-3">
                  	<td class="left">
                   		<?php esc_html_e( 'Twitter Url', 'vw-lawyer-pro-posttype' )?>
                  	</td>
                  	<td class="left" >
                    	<input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_stored_meta['meta-twitterurl'][0]); ?>" />
                  	</td>
                </tr>
                <tr id="meta-4">
                  	<td class="left">
                    	<?php esc_html_e( 'GooglePlus URL', 'vw-lawyer-pro-posttype' )?>
                  	</td>
                  	<td class="left" >
                    	<input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_url($bn_stored_meta['meta-googleplusurl'][0]); ?>" />
                  	</td>
                </tr>
                <tr id="meta-5">
                  	<td class="left">
                  	  	<?php esc_html_e( 'Pintrest URL', 'vw-lawyer-pro-posttype' )?>
                 	</td>
                  	<td class="left" >
                    	<input type="url" name="meta-pintresturl" id="meta-pintresturl" value="<?php echo esc_url($bn_stored_meta['meta-pintresturl'][0]); ?>" />
                  	</td>
                </tr>
                <tr id="meta-6">
                  	<td class="left">
                    	<?php esc_html_e( 'Instagram URL', 'vw-lawyer-pro-posttype' )?>
                  	</td>
                  	<td class="left" >
                    	<input type="url" name="meta-instaurl" id="meta-instaurl" value="<?php echo esc_url($bn_stored_meta['meta-instaurl'][0]); ?>" />
                  	</td>
                </tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function automobile_car_dealer_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['automobile_car_dealer_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['automobile_car_dealer_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'automobile_car_dealer_posttype_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'automobile_car_dealer_posttype_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'automobile_car_dealer_posttype_posttype_testimonial_desigstory']) );
	}
  	if( isset( $_POST[ 'meta-testimonial-url' ] ) ) {
    	update_post_meta( $post_id, 'meta-testimonial-url', esc_url($_POST[ 'meta-testimonial-url']) );
  	}
  	// Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save twitterurl
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }
    // Save pintresturl
    if( isset( $_POST[ 'meta-pintresturl' ] ) ) {
        update_post_meta( $post_id, 'meta-pintresturl', esc_url_raw($_POST[ 'meta-pintresturl' ]) );
    }
    // Save instaurl
    if( isset( $_POST[ 'meta-instaurl' ] ) ) {
        update_post_meta( $post_id, 'meta-instaurl', esc_url_raw($_POST[ 'meta-instaurl' ]) );
    }
}

add_action( 'save_post', 'automobile_car_dealer_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function automobile_car_dealer_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      	$desigstory= get_post_meta($post_id,'automobile_car_dealer_posttype_posttype_testimonial_desigstory',true);
      	$facebook= get_post_meta($post_id,'meta-facebookurl',true);
      	$twitter=get_post_meta($post_id,'meta-twitterurl',true);
      	$googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      	$pintrest=get_post_meta($post_id,'meta-pintresturl',true);
      	$insta=get_post_meta($post_id,'meta-instaurl',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '
          <div class="col-lg-6 testimonial_shortcode">
	        <div class="row">
	           <div class="col-lg-3 col-md-2 col-sm-12 col-12">
				    <img src="'.esc_url($thumb_url).'">
				</div>
	            <div class="col-lg-9 col-md-8 col-sm-10 col-12">
	            	<h4 class="testimonial_name mt-0"> '.get_the_title().' </h4>
	            	<div class="short_text pt-3"><blockquote>'.$excerpt.'</blockquote></div>
	            </div>
	            <div class="col-lg-12 col-md-2 col-sm-2 ">
	            	<div class="testimonial_shortcode_social">';
	                	 if($facebook !=''){
						$testimonial .='<a href="'.esc_url($facebook).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
	                	 } 
	                	 if($twitter !=''){
						$testimonial .='<a href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
	                	 } 
	                	 if($googleplus !=''){
						$testimonial .='<a href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
	                	 } 
	                	 if($pintrest !=''){
						$testimonial .='<a href="'.esc_url($pintrest).'" target="_blank"><i class="fab fa-pinterest-p"></i></a>';
	                	 } 
	                	if($insta !=''){
						$testimonial .='<a href="'.esc_url($insta).'" target="_blank"><i class="fab fa-instagram"></i></a>';
	                	 } 
	                	 $testimonial .='
	               </div>
	            </div>
	        </div>
        </div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','automobile-car-dealer-posttype').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'testimonials', 'automobile_car_dealer_posttype_testimonial_func' );

/* Cars shortcode */
function automobile_car_dealer_posttype_cars_func( $atts, $cat_name ) {
	$cars = '';
	$cars = '<div class="row">';
	$cat_name  =isset($atts['cat_name']) ? esc_html($atts['cat_name']) : '';
	$new = new WP_Query( array( 'post_type' => 'cars', 'automobile_createcar' => $cat_name ) );

    if ( $new->have_posts() ) :

	$k=1;
	
	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        if(get_post_meta($post_id,'meta-cars-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
       	$cars .=' <div class="car_box col-lg-3 col-md-3 col-sm-6 mt-4 mb-4">
                  <div class="image-box ">
                      <img src="'.esc_url($thumb_url).'">
                    <div class="car-box w-100 float-left">
                      <h4 class="car_name"><a href="'.get_the_permalink().'">'.esc_html(get_the_title()).'</a></h4>
                    </div>
                  </div>
                <div class="content_box w-100 float-left">
                  <div class="short_text pt-3"> 
                   '.esc_html(automobile_car_dealer_pro_string_limit_words($excerpt,25)).'</div>
                  <div class="price mt-3">
                    <span class="text-left price-field"> '.esc_html(get_post_meta($post_id,'minimum_price',true)).'</span>
                    <span class="text-right float-right read-more"><a href="'.get_the_permalink().'">'.esc_html('Read More').'<i class="fas fa-long-arrow-alt-right"></i></a></span>
                  </div>                   
                </div>
              </div>';
		if($k%3 == 0){
			$cars.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$cars = '<h2 class="center">'.esc_html__('Post Not Found','automobile-car-dealer-posttype').'</h2>';
  endif;
  $cars.= '</div>';
  return $cars;
}

add_shortcode( 'cars', 'automobile_car_dealer_posttype_cars_func' );