<?php
declare( strict_types=1 );

namespace GroqAIConnector\Models;

use WordPress\AiClient\Providers\Http\DTO\Request;
use WordPress\AiClient\Providers\Http\Enums\HttpMethodEnum;
use WordPress\AiClient\Providers\OpenAiCompatibleImplementation\AbstractOpenAiCompatibleTextGenerationModel;
use GroqAIConnector\Provider\GroqProvider;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class GroqTextGenerationModel extends AbstractOpenAiCompatibleTextGenerationModel {

	protected function prepareGenerateTextParams( array $prompt ): array {
		$params = parent::prepareGenerateTextParams( $prompt );

		$selected = (string) get_option( 'groq_selected_model', 'llama-3.3-70b-versatile' );
		if ( '' !== $selected ) {
			$params['model'] = $selected;
		}

		unset( $params['n'] );
		unset( $params['reasoning_effort'] );

		return $params;
	}

	protected function prepareMessagesParam( array $messages, ?string $system_instruction = null ): array {
		$messages = parent::prepareMessagesParam( $messages, $system_instruction );

		foreach ( $messages as &$message ) {
			if ( ! isset( $message['content'] ) || ! is_array( $message['content'] ) ) {
				continue;
			}
			$all_text = true;
			foreach ( $message['content'] as $key => $part ) {
				if ( ! is_int( $key ) || ! is_array( $part ) || ( $part['type'] ?? '' ) !== 'text' ) {
					$all_text = false;
					break;
				}
			}
			if ( $all_text && count( $message['content'] ) > 0 ) {
				$message['content'] = implode( '', array_map( static fn( $p ) => $p['text'] ?? '', $message['content'] ) );
			}
		}
		unset( $message );

		return $messages;
	}

	protected function createRequest( HttpMethodEnum $method, string $path, array $headers = [], $data = null ): Request {
		return new Request(
			$method,
			GroqProvider::url( '/' . ltrim( $path, '/' ) ),
			$headers,
			$data,
			$this->getRequestOptions()
		);
	}
}
