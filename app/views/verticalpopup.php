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
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'flex flex-col max-h-full mobile:overflow-auto tablet:overflow-hidden', get_the_ID() ); ?>>

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
            (object) $enabledComponents = Preferences::get()->getEnabledComponentsForCurrentDesign();

            (array) $hooks = [
                'title' => [
                    'hook' => 'woocommerce_template_single_title',
                    'priority' => 5
                ],
                'rating' => [
                    'hook' => 'woocommerce_template_single_rating',
                    'priority' => 10
                ],
                'price' => [
                    'hook' => 'woocommerce_template_single_price',
                    'priority' => 10
                ],
                'excerpt' => [
                    'hook' => 'woocommerce_template_single_excerpt',
                    'priority' => 20
                ],
                'add_to_cart' => [
                    'hook' => 'woocommerce_template_single_add_to_cart',
                    'priority' => 30
                ],
                'meta' => [
                    'hook' => 'woocommerce_template_single_meta',
                    'priority' => 40
                ],
                'sharing' => [
                    'hook' => 'woocommerce_template_single_sharing',
                    'priority' => 50
                ],
            ];

            foreach ($hooks as $component => $actionData) {
                if (!$enabledComponents->contain($component)) {
                    remove_action(
                        'woocommerce_single_product_summary', 
                        $actionData['hook'], 
                        $actionData['priority']
                    );
                }
            }

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