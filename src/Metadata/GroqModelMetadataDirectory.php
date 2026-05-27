<?php
declare( strict_types=1 );

namespace GroqAIConnector\Metadata;

use WordPress\AiClient\Messages\Enums\ModalityEnum;
use WordPress\AiClient\Providers\ApiBasedImplementation\AbstractApiBasedModelMetadataDirectory;
use WordPress\AiClient\Providers\Models\DTO\ModelMetadata;
use WordPress\AiClient\Providers\Models\DTO\SupportedOption;
use WordPress\AiClient\Providers\Models\Enums\CapabilityEnum;
use WordPress\AiClient\Providers\Models\Enums\OptionEnum;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class GroqModelMetadataDirectory extends AbstractApiBasedModelMetadataDirectory {

	protected function sendListModelsRequest(): array {
		$model_id = trim( (string) get_option( 'groq_selected_model', 'llama-3.3-70b-versatile' ) );

		if ( '' === $model_id ) {
			return [];
		}

		return [
			$model_id => new ModelMetadata(
				$model_id,
				$model_id,
				[
					CapabilityEnum::textGeneration(),
					CapabilityEnum::chatHistory(),
				],
				[
					new SupportedOption( OptionEnum::systemInstruction() ),
					new SupportedOption( OptionEnum::maxTokens() ),
					new SupportedOption( OptionEnum::temperature() ),
					new SupportedOption( OptionEnum::topP() ),
					new SupportedOption( OptionEnum::stopSequences() ),
					new SupportedOption( OptionEnum::frequencyPenalty() ),
					new SupportedOption( OptionEnum::presencePenalty() ),
					new SupportedOption( OptionEnum::outputMimeType(), [ 'text/plain', 'application/json' ] ),
					new SupportedOption( OptionEnum::outputSchema() ),
					new SupportedOption( OptionEnum::functionDeclarations() ),
					new SupportedOption( OptionEnum::customOptions() ),
					new SupportedOption( OptionEnum::outputModalities(), [ [ ModalityEnum::text() ] ] ),
					new SupportedOption( OptionEnum::inputModalities(), [ [ ModalityEnum::text() ] ] ),
				]
			),
		];
	}
}
