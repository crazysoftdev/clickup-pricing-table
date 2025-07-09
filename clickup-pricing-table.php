<?php
/**
 * Plugin Name:       Clickup Pricing Table
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       clickup-pricing-table
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Registers the block using a `blocks-manifest.php` file, which improves the performance of block type registration.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function create_block_clickup_pricing_table_block_init() {
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
	 *
	 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
	 */
	if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
		wp_register_block_types_from_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		wp_register_block_metadata_collection( __DIR__ . '/build', __DIR__ . '/build/blocks-manifest.php' );
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', 'create_block_clickup_pricing_table_block_init' );


/**
 * Fetches and caches the pricing data from the ClickUp JSON endpoint.
 * Uses the WordPress Transients API for caching to improve performance.
 *
 * @return array|false The decoded pricing data as an associative array, or false on failure.
 */
function clickup_get_pricing_data() {
    // 1. Try to get the data from our cache first.
    $cached_data = get_transient( 'clickup_pricing_data_cache' );
    if ( false !== $cached_data ) {
        return $cached_data;
    }

    // 2. If cache is empty, fetch the data from the remote URL.
    $response = wp_remote_get( 'https://clickup.com/data/pricing/pricing-en.json' );

    // 3. Check for errors during the request.
    if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
        // In a real project, you might log the error here.
        // error_log( 'Failed to fetch ClickUp pricing data: ' . $response->get_error_message() );
        return false;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true ); // Decode as an associative array.

    // 4. Check if the JSON is valid.
    if ( ! is_array( $data ) ) {
        return false;
    }

    // 5. If data is valid, store it in our cache (transient) for 12 hours.
    set_transient( 'clickup_pricing_data_cache', $data, 12 * HOUR_IN_SECONDS );

    return $data;
}