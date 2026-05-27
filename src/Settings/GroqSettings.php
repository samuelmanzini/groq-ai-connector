<?php
declare( strict_types=1 );

namespace GroqAIConnector\Settings;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class GroqSettings {

	private const OPTION_GROUP  = 'groq_settings_group';
	private const OPTION_MODEL  = 'groq_selected_model';
	private const PAGE_SLUG     = 'groq-ai';
	private const DEFAULT_MODEL = 'llama-3.3-70b-versatile';

	public function init(): void {
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_page' ] );
	}

	public function register_settings(): void {
		register_setting( self::OPTION_GROUP, self::OPTION_MODEL, [
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => self::DEFAULT_MODEL,
		] );
	}

	public function register_page(): void {
		add_options_page(
			__( 'Groq AI', 'wp-groq-ai-connector' ),
			__( 'Groq AI', 'wp-groq-ai-connector' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render' ]
		);
	}

	private static function models(): array {
		return [
			'llama-3.3-70b-versatile'       => 'Llama 3.3 70B Versatile',
			'llama-3.1-8b-instant'          => 'Llama 3.1 8B Instant',
			'llama3-70b-8192'               => 'Llama 3 70B',
			'llama3-8b-8192'                => 'Llama 3 8B',
			'mixtral-8x7b-32768'            => 'Mixtral 8x7B (32K ctx)',
			'gemma2-9b-it'                  => 'Gemma 2 9B IT',
			'llama-3.2-90b-vision-preview'  => 'Llama 3.2 90B Vision',
			'llama-3.2-11b-vision-preview'  => 'Llama 3.2 11B Vision',
			'llama-3.2-3b-preview'          => 'Llama 3.2 3B',
			'llama-3.2-1b-preview'          => 'Llama 3.2 1B',
			'deepseek-r1-distill-llama-70b' => 'DeepSeek R1 Distill Llama 70B',
			'qwen-qwq-32b'                  => 'Qwen QwQ 32B',
			'llama-3.3-70b-specdec'         => 'Llama 3.3 70B SpecDec',
		];
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$selected       = (string) get_option( self::OPTION_MODEL, self::DEFAULT_MODEL );
		$models         = self::models();
		$connectors_url = admin_url( 'options-connectors.php' );
		$is_configured  = '' !== (string) get_option( 'connectors_ai_groq_api_key', '' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Groq AI — Modelo padrão', 'wp-groq-ai-connector' ); ?></h1>

			<?php if ( ! $is_configured ) : ?>
			<div class="notice notice-warning inline">
				<p><?php
				// translators: 1: opening link tag, 2: closing link tag.
				printf(
					esc_html__( 'Chave API nao encontrada. Insira sua Groq API Key em %1$sConfiguracoes > Conectores%2$s.', 'wp-groq-ai-connector' ),
					'<a href="' . esc_url( $connectors_url ) . '">',
					'</a>'
				); ?></p>
			</div>
			<?php else : ?>
			<div class="notice notice-success inline">
				<p><?php esc_html_e( 'Groq API Key configurada.', 'wp-groq-ai-connector' ); ?></p>
			</div>
			<?php endif; ?>

			<p><?php
			// translators: 1: opening link tag, 2: closing link tag.
			printf(
				esc_html__( 'A chave API e gerenciada em %1$sConfiguracoes > Conectores%2$s. Escolha aqui qual modelo Groq o WordPress usara por padrao.', 'wp-groq-ai-connector' ),
				'<a href="' . esc_url( $connectors_url ) . '">',
				'</a>'
			); ?></p>

			<form action="options.php" method="post">
				<?php settings_fields( self::OPTION_GROUP ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( self::OPTION_MODEL ); ?>">
								<?php esc_html_e( 'Modelo padrao', 'wp-groq-ai-connector' ); ?>
							</label>
						</th>
						<td>
							<select id="<?php echo esc_attr( self::OPTION_MODEL ); ?>"
									name="<?php echo esc_attr( self::OPTION_MODEL ); ?>"
									class="regular-text">
								<?php foreach ( $models as $id => $label ) : ?>
								<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $selected, $id ); ?>>
									<?php echo esc_html( $label ); ?>
								</option>
								<?php endforeach; ?>
							</select>
							<p class="description">
								<?php
								// translators: %s: link to Groq models documentation.
								printf(
									esc_html__( 'Veja todos os modelos em %s.', 'wp-groq-ai-connector' ),
									'<a href="https://console.groq.com/docs/models" target="_blank" rel="noopener">console.groq.com/docs/models</a>'
								); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Salvar modelo', 'wp-groq-ai-connector' ) ); ?>
			</form>
		</div>
		<?php
	}
}
