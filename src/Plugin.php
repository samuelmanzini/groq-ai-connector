<?php
declare( strict_types=1 );

namespace GroqAIConnector;

use WordPress\AiClient\AiClient;
use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;
use GroqAIConnector\Provider\GroqProvider;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Plugin {

	public function init(): void {
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
		add_action( 'init', [ $this, 'register_provider' ], 5 );
		add_action( 'init', [ $this, 'register_auth' ], 15 );
		add_action( 'wp_connectors_init', [ $this, 'register_connector' ] );
		add_filter( 'wpai_preferred_text_models', [ $this, 'prepend_preferred_models' ] );
		( new Settings\GroqSettings() )->init();
	}

	public function load_textdomain(): void {
		load_plugin_textdomain(
			'groq-ai-connector',
			false,
			dirname( plugin_basename( GROQ_CONNECTOR_FILE ) ) . '/languages'
		);
	}

	public function register_provider(): void {
		if ( ! class_exists( AiClient::class ) ) {
			return;
		}
		$registry = AiClient::defaultRegistry();
		if ( $registry->hasProvider( GroqProvider::class ) ) {
			return;
		}
		$registry->registerProvider( GroqProvider::class );
	}

	public function register_auth(): void {
		if ( ! class_exists( AiClient::class ) ) {
			return;
		}
		$registry = AiClient::defaultRegistry();
		if ( ! $registry->hasProvider( 'groq' ) ) {
			return;
		}
		$auth = $registry->getProviderRequestAuthentication( 'groq' );
		if ( null !== $auth ) {
			return;
		}
		$registry->setProviderRequestAuthentication( 'groq', new ApiKeyRequestAuthentication( $this->resolve_api_key() ) );
	}

	public function register_connector( $registry ): void {
		if ( ! class_exists( 'WP_Connector_Registry' ) || ! ( $registry instanceof \WP_Connector_Registry ) ) {
			return;
		}
		$registry->register( 'groq', [
			'name'        => 'Groq',
			'description' => __( 'Ultra-fast AI inference with Groq LPU. Run Llama, Mixtral, Gemma and more.', 'groq-ai-connector' ),
			'logo_url'    => plugin_dir_url( GROQ_CONNECTOR_FILE ) . 'assets/icon-256x256.png',
			'type'        => 'ai_provider',
			'authentication' => [
				'method'          => 'api_key',
				'credentials_url' => 'https://console.groq.com/keys',
				'setting_name'    => 'connectors_ai_groq_api_key',
			],
			'plugin' => [
				'file' => plugin_basename( GROQ_CONNECTOR_FILE ),
			],
		] );
	}

	public function prepend_preferred_models( array $models ): array {
		$selected = (string) get_option( 'groq_selected_model', 'llama-3.3-70b-versatile' );
		if ( '' !== $selected ) {
			array_unshift( $models, [ 'groq', $selected ] );
		}
		return $models;
	}

	private function resolve_api_key(): string {
		$env = getenv( 'GROQ_API_KEY' );
		if ( false !== $env && '' !== $env ) {
			return trim( (string) $env );
		}
		if ( defined( 'GROQ_API_KEY' ) ) {
			$c = constant( 'GROQ_API_KEY' );
			if ( is_string( $c ) && '' !== $c ) {
				return $c;
			}
		}
		return (string) get_option( 'connectors_ai_groq_api_key', '' );
	}
}
