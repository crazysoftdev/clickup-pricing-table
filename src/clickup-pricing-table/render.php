<?php
/**
 * PHP file to render the pricing table block on the front-end.
 *
 * @package ClickUpPricingTable
 */

// Fetch the block attributes (e.g., our showAIBanner toggle).
$show_ai_banner = $attributes['showAIBanner'] ?? false;

// Fetch the cached pricing data.
$pricing_data = clickup_get_pricing_data();

// Graceful failure: If data isn't available, show a notice to admins and nothing to the public.
if ( ! $pricing_data || ! isset( $pricing_data['plans'] ) ) {
    if ( current_user_can( 'edit_posts' ) ) {
        echo '<p style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 1rem;"><strong>Admin Notice:</strong> Could not load ClickUp pricing data.</p>';
    }
    return;
}

// Define which plans we want to display from the JSON data.
$plans_to_display = [ 'Free Forever', 'Unlimited', 'Business', 'Enterprise' ];
$all_plans        = array_filter(
    $pricing_data['plans'],
    fn( $plan ) => in_array( $plan['plan_name'], $plans_to_display, true )
);

// Get the block wrapper attributes.
$wrapper_attributes = get_block_wrapper_attributes();

?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="pricing-table-container">
        <?php foreach ( $all_plans as $plan ) : ?>
            <?php
            $is_popular = ( 'Business' === $plan['plan_name'] );
            $plan_class = 'plan-column';
            if ( $is_popular ) {
                $plan_class .= ' plan-column--popular';
            }
            ?>
            <div class="<?php echo esc_attr( $plan_class ); ?>">
                <?php if ( $is_popular ) : ?>
                    <div class="popular-badge">Most Popular</div>
                <?php endif; ?>
                <h3 class="plan-name"><?php echo esc_html( $plan['plan_name'] ); ?></h3>
                <p class="plan-description"><?php echo esc_html( $plan['plan_subtitle'] ); ?></p>
                <div class="plan-price">
                    <?php echo 'Free' === $plan['pricing']['monthly']['price'] ? 'FREE' : '$' . esc_html( $plan['pricing']['monthly']['price'] ); ?>
                </div>
                <?php if ( 'Free' !== $plan['pricing']['monthly']['price'] && isset( $plan['pricing']['monthly']['period_text'] ) ) : ?>
                    <p class="plan-billing-cycle"><?php echo esc_html( $plan['pricing']['monthly']['period_text'] ); ?></p>
                <?php endif; ?>

                <a href="https://clickup.com/pricing" target="_blank" rel="noopener noreferrer" class="plan-button">
                    <?php echo 'Enterprise' === $plan['plan_name'] ? 'Contact Sales' : 'Get Started'; ?>
                </a>

                <div class="plan-features">
                    <ul class="features-visible">
                        <?php foreach ( array_slice( $plan['features'], 0, 5 ) as $feature ) : ?>
                            <li><?php echo esc_html( $feature['feature_name'] ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if ( count( $plan['features'] ) > 5 ) : ?>
                        <ul class="features-extended" hidden>
                            <?php foreach ( array_slice( $plan['features'], 5 ) as $feature ) : ?>
                                <li><?php echo esc_html( $feature['feature_name'] ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button class="features-toggle-button" aria-expanded="false">See more features</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ( $show_ai_banner ) : ?>
        <div class="ai-banner">
            <div class="ai-banner-content">
                <h3>The world's most complete work AI, starting at $9 per month</h3>
                <p>ClickUp Brain is a no Brainer. One AI to manage your work, at a fraction of the cost.</p>
            </div>
            <a href="#" class="ai-banner-button">Try for free</a>
        </div>
    <?php endif; ?>
</div>