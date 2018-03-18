<?php
namespace App;

function remove_default_image_sizes( $sizes) {
    unset( $sizes['thumbnail']);
    unset( $sizes['medium']);
    unset( $sizes['large']);
    return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'App\remove_default_image_sizes');

//Custom Image sizes
add_theme_support( 'post-thumbnails' );

//rectangles (soft crop)
add_image_size( 'rec-small', 800, 500, true);
add_image_size( 'rec-medium', 1600, 1000, true);
add_image_size( 'rec-large', 1920, 1200, true);

//squares (hard crop)
add_image_size( 'squ-small', 800, 800, true );
add_image_size( 'squ-medium', 1600, 1600, true );
add_image_size( 'squ-large', 1920, 1920, true );

//wide rectangles (hard crop)
add_image_size( 'wid-small', 800, 400, true );
add_image_size( 'wid-medium', 1600, 800, true );
add_image_size( 'wid-large', 1920, 960, true );

function my_acf_admin_head() { ?>
	<style type="text/css">
    /* Styles for ACF Dashboard go here */
	</style>
<?php }
add_action('acf/input/admin_head', 'App\my_acf_admin_head');

// Set ACF 5 license key on theme activation. Stick in your functions.php or equivalent.
function auto_set_license_keys() {
  if ( ! get_option( 'acf_pro_license' ) && defined( 'ACF_5_KEY' ) ) {
    $save = array(
    'key' => ACF_5_KEY,
    'url' => home_url()
  );
  $save = maybe_serialize($save);
  $save = base64_encode($save);
    update_option( 'acf_pro_license', $save );
  }
}
add_action( 'after_switch_theme', 'auto_set_license_keys' );

//remove <p> tags around category filter
remove_filter('term_description','wpautop');
