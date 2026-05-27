=== Groq AI Connector ===
Contributors:      samuelcosta
Tags:              ai, groq, llama, connector, text generation
Requires at least: 7.0
Tested up to:      7.0
Requires PHP:      8.1
Stable tag:        0.2.1
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Ultra-fast AI connector for Groq LPU — registers Groq in the WordPress 7.0 Connectors Gallery and PHP AI Client.

== Description ==

**Groq AI Connector** integrates the [Groq](https://groq.com) inference platform with the WordPress 7.0 native AI system.

Groq's LPU (Language Processing Unit) delivers inference speeds significantly faster than traditional GPU-based providers, making it ideal for AI-powered editorial workflows where latency matters.

= What this plugin does =

* Registers **Groq** as a provider in the WordPress 7.0 **Connectors Gallery** (Settings → Connectors), alongside Anthropic, Google and OpenAI.
* Integrates Groq with the **PHP AI Client** bundled in the `ai` plugin, enabling WordPress AI features (title generation, excerpt generation, alt text, content classification, and more) to use Groq models.
* Adds a **Settings → Groq AI** page where you choose the default model from all available Groq models.
* Your API key is stored securely in the native Connectors system — not duplicated in a separate settings field.

= Available models =

* Llama 3.3 70B Versatile *(recommended)*
* Llama 3.1 8B Instant *(fastest)*
* Llama 3 70B / 8B
* Mixtral 8x7B (32K context)
* Gemma 2 9B IT
* Llama 3.2 90B / 11B Vision (preview)
* DeepSeek R1 Distill Llama 70B
* Qwen QwQ 32B
* And more — see [console.groq.com/docs/models](https://console.groq.com/docs/models)

= Requirements =

* WordPress 7.0 or higher
* The [AI plugin](https://wordpress.org/plugins/ai/) must be installed and active
* PHP 8.1 or higher
* A free Groq API key from [console.groq.com](https://console.groq.com)

= Privacy =

This plugin sends text prompts to the Groq API (api.groq.com) when WordPress AI features are used. No data is stored by this plugin beyond the selected model preference and the API key. Please review [Groq's privacy policy](https://groq.com/privacy-policy/) before use.

== Installation ==

= Automatic installation =

1. Log in to your WordPress admin panel.
2. Go to **Plugins → Add New → Upload Plugin**.
3. Upload the `groq-connector-v2.1.0.zip` file.
4. Click **Install Now**, then **Activate**.

= Manual installation via FTP =

1. Extract the ZIP file on your computer.
2. Upload the `groq-ai-connector` folder to `/wp-content/plugins/`.
3. Activate the plugin in **Plugins → Installed Plugins**.

= After activation =

1. Go to **Settings → Connectors**.
2. Find the **Groq** card and enter your API key (get one free at [console.groq.com/keys](https://console.groq.com/keys)).
3. Go to **Settings → Groq AI** to choose your preferred model.
4. WordPress AI features will now use Groq automatically.

== Frequently Asked Questions ==

= Do I need a paid Groq account? =

No. Groq offers a free tier with generous rate limits, which is sufficient for most editorial use cases. See [groq.com/pricing](https://groq.com/pricing) for details.

= Where do I enter my API key? =

In **Settings → Connectors**, not in the Groq AI settings page. This follows the standard WordPress 7.0 Connectors API pattern — all API keys are managed centrally in one place.

= Why is there a separate "Groq AI" settings page? =

The Connectors Gallery handles API key management. The **Settings → Groq AI** page exists specifically for model selection — so you can choose which Groq model WordPress uses by default without touching the key.

= Which model should I use? =

For general editorial tasks (title generation, excerpts, summaries): **Llama 3.3 70B Versatile** — best balance of quality and speed.
For high-volume or real-time tasks: **Llama 3.1 8B Instant** — extremely fast with good quality.

= Does this plugin support image generation? =

Not yet. Groq currently focuses on text generation. Image generation support may be added in a future release if Groq introduces image models.

= Will this work with third-party plugins that use the WordPress AI Client? =

Yes. Any plugin that calls `wp_ai_client_prompt()` or uses the AI Service will automatically use Groq when it is the first available provider in the preference list.

= Can I use multiple AI providers at the same time? =

Yes. WordPress 7.0 supports multiple configured providers. Groq will be used first for text generation. If a feature requires a capability Groq does not support (e.g. image generation), WordPress will fall back to the next configured provider.

= Where can I report bugs or request features? =

Please open an issue at the plugin's GitHub repository or use the WordPress.org support forum.

== Screenshots ==

1. Groq listed in the WordPress 7.0 Connectors Gallery alongside Anthropic, Google and OpenAI.
2. Settings → Groq AI — model selection dropdown.
3. WordPress AI features (title generation, excerpt) using Groq in the block editor.

== Changelog ==

= 2.1.0 =
* Full integration with the PHP AI Client via `AbstractApiProvider`.
* Added `wpai_preferred_text_models` filter so Groq is used first for text generation.
* Settings page now shows only model selection (API key moved to Connectors Gallery).
* Autoloader for `src/` classes using PSR-4 convention.
* Added `GroqProvider`, `GroqProviderAvailability`, `GroqTextGenerationModel`, `GroqModelMetadataDirectory`.
* API key resolution priority: environment variable → PHP constant → WordPress option.

= 2.0.0 =
* Rewrote registration to use the correct `wp_connectors_init` hook and `WP_Connector_Registry`.
* Removed non-existent hooks (`wp_ai_init`, `wp_ai_get_providers`, `wp_ai_providers`).
* Added model selection dropdown to settings page.

= 1.1.5 =
* Initial release — experimental brute-force connector registration.

== Upgrade Notice ==

= 2.1.0 =
Major rewrite. Before upgrading, deactivate and delete the old version via WP Admin or FTP. Then install this ZIP fresh. Your API key stored in the Connectors Gallery will be preserved.
