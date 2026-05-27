<?php
/**
 * Plugin Name: Groq AI Connector
 * Plugin URI:  https://wordpress.org/plugins/groq-ai-connector
 * Description: Ultra-fast AI connector for Groq LPU — registers Groq in the WordPress 7.0 Connectors Gallery and PHP AI Client.
 * Version:     2.1.0
 * Author:      Samuel Costa
 * Author URI:  https://samuel-costa.com
 * License:     GPLv2 or later
 * Text Domain: groq-ai-connector
 * Requires at least: 7.0
 * Requires PHP: 8.1
 * Requires Plugins: ai
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GROQ_CONNECTOR_FILE', __FILE__ );
define( 'GROQ_CONNECTOR_DIR',  plugin_dir_path( __FILE__ ) );
define( 'GROQ_CONNECTOR_MIN_WP', '7.0' );

spl_autoload_register( static function ( string $class ): void {
	$prefix = 'GroqAIConnector\\';
	if ( strpos( $class, $prefix ) !== 0 ) {
		return;
	}
	$relative = substr( $class, strlen( $prefix ) );
	$file     = GROQ_CONNECTOR_DIR . 'src/' . str_replace( '\\', '/', $relative ) . '.php';
	if ( file_exists( $file ) ) {
		require_once $file;
	}
} );

function groq_connector_load(): void {
	if ( ! is_wp_version_compatible( GROQ_CONNECTOR_MIN_WP ) ) {
		add_action( 'admin_notices', static function (): void {
			global $wp_version;
			echo '<div class="notice notice-error"><p>';
			printf(
				esc_html__( 'Groq AI Connector requires WordPress %1$s or higher. You are running %2$s.', 'groq-ai-connector' ),
				esc_html( GROQ_CONNECTOR_MIN_WP ),
				esc_html( $wp_version )
			);
			echo '</p></div>';
		} );
		return;
	}

	if ( ! class_exists( \WordPress\AiClient\AiClient::class ) ) {
		add_action( 'admin_notices', static function (): void {
			echo '<div class="notice notice-error"><p>';
			esc_html_e( 'Groq AI Connector requires the WordPress AI plugin (ai) to be active.', 'groq-ai-connector' );
			echo '</p></div>';
		} );
		return;
	}

	( new GroqAIConnector\Plugin() )->init();
}

groq_connector_load();
