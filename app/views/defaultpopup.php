<?php

use SmartQuickView\App\Data\Preferences\Preferences;

while ($self->products->have_posts()) {
    $self->products->the_post();

    /**
     * Adapted from plugins/woocommerce/templates/content-single-product.php
     */

    if ( post_password_required() ) {
        echo get_the_password_form(); // WPCS: XSS ok.
        return;
    }
    ?>
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'flex mobile:flex-col tablet:flex-row  tablet:space-y-0 tablet:space-x-4 max-h-full mobile:overflow-auto tablet:overflow-hidden', $product ); ?>>

        <div class="">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
        </div>

        <div class="summary entry-summary max-h-full tablet:overflow-auto !mb-0 px-[32px] py-[28px]">
            <?php
            /**
             * Hook: woocommerce_single_product_summary.
             *
             * @hooked woocommerce_template_single_title - 5
             * @hooked woocommerce_template_single_rating - 10
             * @hooked woocommerce_template_single_price - 10
             * @hooked woocommerce_template_single_excerpt - 20
             * @hooked woocommerce_template_single_add_to_cart - 30
             * @hooked woocommerce_template_single_meta - 40
             * @hooked woocommerce_template_single_sharing - 50
             * @hooked WC_Structured_Data::generate_product_data() - 60
             */
            do_action( 'woocommerce_single_product_summary' );
            ?>
        </div>

    </div>
    <?php
}
wp_reset_postdata();