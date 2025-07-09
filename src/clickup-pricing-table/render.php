<?php
/**
 * PHP file to render the pricing table block on the front-end.
 *
 * @package ClickUpPricingTable
 */

$pricing_data = clickup_get_pricing_data();

// If data fetching failed, render nothing on the front-end.
// For logged-in admins, show an error message.
if ( ! $pricing_data ) {
    if ( current_user_can( 'edit_posts' ) ) {
        echo '<p style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 1rem;">';
        echo '<strong>Admin Notice:</strong> Could not load ClickUp pricing data. The API might be down or the cache is empty.';
        echo '</p>';
    }
    return;
}

// For now, we'll just dump the data to prove it's working.
// We will replace this with our actual HTML structure in Phase 4.
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <pre>
        <code>
            <?php print_r( $pricing_data ); ?>
        </code>
    </pre>
</div>