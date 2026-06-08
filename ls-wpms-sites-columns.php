<?php
/**
 * @package All Sites Columns for Multisite
 */
 
/**
 *  Plugin Name: All Sites Columns for Multisite
 *  Plugin URI:  https://wordpress.org/plugins/all-sites-columns-for-multisite/
 *  Description: For Multisite only. The plugin adds sortable columns to the sites network admin table.
 *  Version:     1.0.1
 *  Author:      lenasterg
 *  Author URI:  https://infra-nets.cti.gr/el/
 *  License:     GPLv2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpmu_blogs_columns', 'lsscms_add_sites_column_heading', 99 );

/**
 * Adds the columns headings.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function lsscms_add_sites_column_heading( $columns ) {
	$x = array(
		'cb'      => $columns['cb'],
		'blog_id' => esc_html__( 'Blog ID' ),
	);

	$columns_new             = array_merge( $x, $columns );
	$columns_new['public']   = esc_html__( 'Public' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
	$columns_new['deleted']  = esc_html__( 'Deleted' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
	$columns_new['archived'] = esc_html__( 'Archived' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
	$columns_new['mature']   = esc_html__( 'Mature' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
	$columns_new['spam']     = esc_html__( 'Spam' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
	$columns_new['site_id']  = esc_html__( 'Site ID', 'all-sites-columns-for-multisite' );

	return $columns_new;
}

add_filter( 'manage_sites-network_sortable_columns', 'lsscms_columns_sortable' );

/**
 * Adds the sortable columns.
 *
 * @param array $sortable_columns Existing sortable columns.
 * @return array
 */
function lsscms_columns_sortable( $sortable_columns ) {
	$sortable_columns['blog_id']  = 'blog_id';
	$sortable_columns['public']   = 'public';
	$sortable_columns['deleted']  = 'deleted';
	$sortable_columns['archived'] = 'archived';
	$sortable_columns['mature']   = 'mature';
	$sortable_columns['spam']     = 'spam';
	$sortable_columns['site_id']  = 'site_id';

	return $sortable_columns;
}

add_action( 'manage_sites_custom_column', 'lsscms_columns_content', 10, 2 );

/**
 * Adds the content of the columns.
 *
 * @global wpdb $wpdb WordPress database object.
 * @param string $column_name Name of the column.
 * @param int    $blog_id     ID of the blog.
 */
function lsscms_columns_content( $column_name, $blog_id ) {
	global $wpdb;
	
	// Find the columns of wp_blogs table.
	$existing_columns = $wpdb->get_col( 'DESC ' . $wpdb->base_prefix . 'blogs' );
	if ( in_array( $column_name, $existing_columns, true ) ) {
		$prepared_statement = $wpdb->prepare( 'SELECT ' . esc_sql( $column_name ) . ' from ' . $wpdb->base_prefix . 'blogs where blog_id= %d', $blog_id );
		
		$content = $wpdb->get_var( $prepared_statement );

		echo esc_html( $content );
	}
}
