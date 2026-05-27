<?php
declare( strict_types=1 );

namespace GroqAIConnector\Provider;

use WordPress\AiClient\AiClient;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\Http\DTO\ApiKeyRequestAuthentication;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class GroqProviderAvailability implements ProviderAvailabilityInterface {

	public function isConfigured(): bool {
		if ( ! class_exists( AiClient::class ) ) {
			return false;
		}
		$registry = AiClient::defaultRegistry();
		if ( ! $registry->hasProvider( 'groq' ) ) {
			return false;
		}
		$auth = $registry->getProviderRequestAuthentication( 'groq' );
		if ( null === $auth ) {
			return false;
		}
		if ( $auth instanceof ApiKeyRequestAuthentication ) {
			return '' !== $auth->getApiKey();
		}
		return true;
	}
}
