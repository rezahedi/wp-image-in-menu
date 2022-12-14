<?php
//Add Custom scripts in Admin
add_action( 'admin_enqueue_scripts', 'rz_custom_script' );

function rz_custom_script(){

	wp_enqueue_script( 'rz-admin-script', RZ_MENU_IMG_URL . '/assets/js/rz-admin-script.js' );
	wp_localize_script( 'rz-admin-script', 'deleteimg_ajax', array( 'ajax_url' => admin_url('admin-ajax.php')) );
	wp_localize_script( 'rz-admin-script', 'editimg', RZ_MENU_IMG_URL . 'assets/images/edit-icon.svg' );
	wp_localize_script( 'rz-admin-script', 'deleteimg', RZ_MENU_IMG_URL . 'assets/images/delete-icon.svg' );
	wp_enqueue_style( 'rz-admin-style', RZ_MENU_IMG_URL . '/assets/css/rz-style.css' );

	if (is_admin ()){
		wp_enqueue_media ();
	}
}

//Update menu custom field
add_action( 'wp_update_nav_menu_item', 'rz_update_custom_img_field', 10, 3 );

function rz_update_custom_img_field( $menu_id, $menu_item_db_id, $args ) {

	// Verify this came from our screen and with proper authorization.
	if ( ! isset( $_POST['_menu_list_image_nonce_name'] ) || ! wp_verify_nonce( $_POST['_menu_list_image_nonce_name'], 'menu_list_image_nonce' ) ) {
		return $menu_id;
	}

	if ( is_array($_REQUEST['menu-item-image']) || is_array($_REQUEST['menu-item-img-position']) ) {
		$image_value = sanitize_text_field($_REQUEST['menu-item-image'][$menu_item_db_id]);
		$image_position = sanitize_text_field($_REQUEST['menu-item-img-position'][$menu_item_db_id]);
		if ( $image_value ) {
			update_post_meta( $menu_item_db_id, '_menu_list_image', $image_value );
			update_post_meta( $menu_item_db_id, '_menu_list_image_position', $image_position );
		}
	}
}

//Add menu custom field
add_action( 'wp_nav_menu_item_custom_fields', 'rz_customfield_menu_image', 10, 2 );

function rz_customfield_menu_image( $item_id, $item ) {

	wp_nonce_field( 'menu_list_image_nonce', '_menu_list_image_nonce_name' );
	$menu_image = get_post_meta( $item_id, '_menu_list_image', true );
	$menu_image_position = get_post_meta( $item_id, '_menu_list_image_position', true );
	?>
	<div class="field-custom_menu_meta" style="margin: 5px 0;">
		<div class="menu-img">
		    <p><?php _e( 'Image', 'rz-menu-img' ); ?></p>
			<?php if($menu_image){ ?>
				<div class="menu-img-block menu-block-<?php echo $item_id; ?>">
					<ul class="menu-actions">
						<li><a href="javascript:void(0);" class="edit-btn" id="upload-image-<?php echo $item_id; ?>"  data-id="<?php echo $item_id; ?>"><img src="<?php echo RZ_MENU_IMG_URL . 'assets/images/edit-icon.svg'; ?>" alt="edit"></a></li>
						<li><a href="javascript:void(0);" class="close-btn" data-id="<?php echo $item_id; ?>"><img src="<?php echo RZ_MENU_IMG_URL . 'assets/images/delete-icon.svg'; ?>" alt="delete"></a></li>
					</ul>
				    <img class="menu-image upload-image-<?php echo $item_id; ?>" src="<?php echo wp_get_attachment_image_src( $menu_image )[0]; ?>" width="120" height="120">
				</div>
			<?php } ?>
			<input class="widefat custom_media_url img_txt-<?php echo $item_id; ?>" id="edit-menu-item-image-<?php echo $item_id; ?>" name="menu-item-image[<?php echo $item_id; ?>]" type="hidden" value="<?php echo $menu_image; ?>">
			<input type="button" class="button upload-image widefat" data-id="<?php echo $item_id; ?>" id="upload-image-<?php echo $item_id; ?>" value="Upload Image" style="margin-top:5px;" />
		</div>
		<div class="img-position" style="margin: 5px 0;">
			<p><?php _e( 'Image Position', 'rz-menu-img' ); ?></p>
			<label>
				<input type="radio" name="menu-item-img-position[<?php echo $item_id; ?>]" value="before" <?php if($menu_image_position == 'before'){ ?>checked <?php }else{ echo 'checked'; } ?>>
				<span><?php _e( 'Before', 'rz-menu-img' ); ?></span>
			</label>
			<label>
				<input type="radio" name="menu-item-img-position[<?php echo $item_id; ?>]" value="after" <?php if($menu_image_position == 'after'){ echo 'checked'; } ?>>
				<span><?php _e( 'After', 'rz-menu-img' ); ?></span>
			</label>
		</div>
	</div>
	<?php
}

// Add custom menu fields in the Theme Customizer
// TODO: read this: https://wordpress.stackexchange.com/questions/372493/add-settings-to-menu-items-in-the-customizer
// add_action( 'wp_nav_menu_item_custom_fields_customize_template', 'rz_customfield_menu_image_customizer' );
function rz_customfield_menu_image_customizer($item_id, $item) { 

	global $wp_roles;

	/**
	* Pass the menu item to the filter function.
	* This change is suggested as it allows the use of information from the menu item (and
	* by extension the target object) to further customize what filters appear during menu
	* construction.
	*/
	$display_roles = apply_filters( 'nav_menu_roles', $wp_roles->role_names );
	if( ! $display_roles ) return;

	wp_nonce_field( 'menu_list_image_nonce', '_menu_list_image_nonce_name' );
	$menu_image = get_post_meta( $item_id, '_menu_list_image', true );
	$menu_image_position = get_post_meta( $item_id, '_menu_list_image_position', true );
	?>
	<p class="field-nav_menu_logged_in_out nav_menu_logged_in_out nav_menu_logged_in_out-thin">
		<fieldset>
			<legend><?php _e( 'Display Mode', 'nav-menu-roles' ); ?></legend>

			<label for="edit-menu-item-role_logged_in-{{ data.menu_item_id }}">
				<input type="radio" id="edit-menu-item-role_logged_in-{{ data.menu_item_id }}" class="edit-menu-item-logged_in_out" value="in" name="menu-item-role_logged_in" />
				<?php _e( 'Logged In Users', 'nav-menu-roles' ); ?><br/>
			</label>
		</fieldset>
	</p>
	<?php
}


// Decorates a menu item object with the shared navigation menu item properties.
add_filter( 'wp_setup_nav_menu_item', 'rz_add_custom_menu_fields' );

function rz_add_custom_menu_fields( $menu_item ) {

	$menu_item->image = get_post_meta( $menu_item->ID, '_menu_list_image', true );
	$menu_item->image_position = get_post_meta( $menu_item->ID, '_menu_list_image_position', true );

	return $menu_item;
}


//Display Image in Menu
add_filter( 'nav_menu_item_title', 'rz_display_img_menu', 10, 4 );

function rz_display_img_menu( $title, $item, $args, $depth ) {

	// Add span tag to title
	$title = "<span>" . $title . "</span>";
	
	// Get post metas
	$menu_image_id = get_post_meta( $item->ID, '_menu_list_image', true );
	$menu_image_pos = get_post_meta( $item->ID, '_menu_list_image_position', true );

	// Return title if no image id
	if ( !$menu_image_id ) return $title;
	
	// Add image before/after title
	if ( $menu_image_pos == 'before' )
		$title = wp_get_attachment_image($menu_image_id, 'thumbnail') . $title;

	else
		$title .= wp_get_attachment_image($menu_image_id, 'thumbnail');

	return $title;
}


//Add class in menu items
add_filter('nav_menu_css_class' , 'rz_add_custom_class' , 10 , 2);

function rz_add_custom_class($classes, $item){

	// Get post metas
	$menu_image_pos = get_post_meta( $item->ID, '_menu_list_image_position', true );

	$classes[] = 'wp-menu-img';
	
	if ( $menu_image_pos == 'before' )
		$classes[] = 'wp-menu-img-before';	
	
	else
		$classes[] = 'wp-menu-img-after';

	return $classes;
}


//Delete Image in Menu
add_action('wp_ajax_del_img', 'rz_delete_img_menu');
add_action('wp_ajax_nopriv_del_img', 'rz_delete_img_menu');

function rz_delete_img_menu(){

	delete_post_meta( sanitize_text_field($_POST['menu_id']), '_menu_list_image' );
	update_post_meta( sanitize_text_field($_POST['menu_id']), '_menu_list_image_position', 'before' );

	exit;
}