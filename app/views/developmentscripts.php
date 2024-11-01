<?php 
// ONLY USED FOR THE DEVELOPMENT SCRIPTS, 
// THIS IS IGNORED ON LIVE SITES
// 
// we need to add crossorigin in order for the error handler to work on
// the dev server

wp_register_script($self->appId, '');
wp_enqueue_script($self->appId);

/**
 * Required WooCommerce variation & gallery components
 */
wp_enqueue_script('wc-single-product');
wp_enqueue_script( 'zoom' );
wp_enqueue_script( 'flexslider' );
wp_enqueue_script( 'photoswipe-ui-default' );
wp_enqueue_style( 'photoswipe' );
wp_enqueue_style( 'photoswipe-default-skin' );
add_action( 'wp_footer', 'woocommerce_photoswipe' );

add_action('print_footer_scripts', function() {
    ?>
        <script crossorigin src="http://localhost:3000/static/js/bundle.js<?php echo esc_attr('?v='.time()) ?>"></script>
        
    <?php 
}, 1000);