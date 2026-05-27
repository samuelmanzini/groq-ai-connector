<?php
declare( strict_types=1 );

namespace GroqAIConnector\Provider;

use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiProvider;
use WordPress\AiClient\Providers\Contracts\ModelMetadataDirectoryInterface;
use WordPress\AiClient\Providers\Contracts\ProviderAvailabilityInterface;
use WordPress\AiClient\Providers\DTO\ProviderMetadata;
use WordPress\AiClient\Providers\Enums\ProviderTypeEnum;
use WordPress\AiClient\Providers\Http\Enums\RequestAuthenticationMethod;
use WordPress\AiClient\Providers\Models\Contracts\ModelInterface;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use GroqAIConnector\Metadata\GroqModelMetadataDirectory;
use GroqAIConnector\Models\GroqTextGenerationModel;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class GroqProvider extends AbstractApiProvider {

	private const BASE_URL = 'https://api.groq.com/openai/v1';

	protected static function baseUrl(): string {
		return self::BASE_URL;
	}

	protected static function createProviderMetadata(): ProviderMetadata {
		return new ProviderMetadata(
			'groq',
			__( 'Groq', 'groq-ai-connector' ),
			ProviderTypeEnum::cloud(),
			'',
			RequestAuthenticationMethod::apiKey(),
			__( 'Ultra-fast AI inference with Groq LPU.', 'groq-ai-connector' ),
			GROQ_CONNECTOR_DIR . 'assets/icon-256x256.png'
		);
	}

	protected static function createModel( ModelMetadata $model_metadata, ProviderMetadata $provider_metadata ): ModelInterface {
		return new GroqTextGenerationModel( $model_metadata, $provider_metadata );
	}

	protected static function createProviderAvailability(): ProviderAvailabilityInterface {
		return new GroqProviderAvailability();
	}

	protected static function createModelMetadataDirectory(): ModelMetadataDirectoryInterface {
		return new GroqModelMetadataDirectory();
	}
}
