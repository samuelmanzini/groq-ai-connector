# Groq AI Connector for WordPress

![Banner](assets/banner-772x250.jpg)

**Ultra-fast AI connector for Groq LPU** — registers Groq in the WordPress 7.0 Connectors Gallery and integrates it with the PHP AI Client.

[![License: GPLv2](https://img.shields.io/badge/License-GPLv2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Requires WordPress](https://img.shields.io/badge/WordPress-7.0%2B-21759b)](https://wordpress.org)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-777bb4)](https://www.php.net)
[![Stable tag](https://img.shields.io/badge/version-0.2.1-orange)](https://github.com/samuelmanzini/groq-ai-connector/releases)

---

## What this plugin does

- Registers **Groq** as a provider in the WordPress 7.0 **Connectors Gallery** (Settings → Connectors), alongside Anthropic, Google and OpenAI.
- Integrates Groq with the **PHP AI Client** bundled in the `ai` plugin, enabling WordPress AI features (title generation, excerpt generation, alt text, content classification, and more) to use Groq models.
- Adds a **Settings → Groq AI** page where you choose the default model from all available Groq models.
- Your API key is stored securely in the native Connectors system — not duplicated in a separate settings field.

Groq's LPU (Language Processing Unit) delivers inference speeds significantly faster than traditional GPU-based providers, making it ideal for AI-powered editorial workflows where latency matters.

---

## Requirements

- WordPress 7.0 or higher
- The [AI plugin](https://wordpress.org/plugins/ai/) must be installed and active
- PHP 8.1 or higher
- A free Groq API key from [console.groq.com](https://console.groq.com)

---

## Installation

1. Download the latest ZIP from the [Releases](https://github.com/samuelmanzini/groq-ai-connector/releases) page.
2. In WordPress admin, go to **Plugins → Add New → Upload Plugin**.
3. Upload the ZIP and click **Install Now**, then **Activate**.

**Or manually via FTP:**

1. Clone or download this repository.
2. Upload the `groq-ai-connector` folder to `/wp-content/plugins/`.
3. Activate the plugin in **Plugins → Installed Plugins**.

---

## Setup

1. Go to **Settings → Connectors**.
2. Find the **Groq** card and enter your API key (get one free at [console.groq.com/keys](https://console.groq.com/keys)).
3. Go to **Settings → Groq AI** to choose your preferred model.
4. WordPress AI features will now use Groq automatically.

---

## Available models

| Model | Best for |
|---|---|
| Llama 3.3 70B Versatile | General editorial tasks — best quality/speed balance |
| Llama 3.1 8B Instant | High-volume or real-time tasks — extremely fast |
| Llama 3 70B / 8B | Stable general-purpose models |
| Mixtral 8x7B (32K ctx) | Long-context tasks |
| Gemma 2 9B IT | Lightweight inference |
| Llama 3.2 90B / 11B Vision | Vision tasks (preview) |
| DeepSeek R1 Distill Llama 70B | Reasoning tasks |
| Qwen QwQ 32B | Reasoning tasks |

Full list: [console.groq.com/docs/models](https://console.groq.com/docs/models)

---

## Plugin structure

```
groq-ai-connector/
├── groq-ai-connector.php          # Main plugin file
├── uninstall.php                  # Cleanup on deletion
├── readme.txt                     # WordPress.org readme
├── license.txt                    # GPLv2
├── assets/                        # Icons and banners
└── src/
    ├── Plugin.php                 # Bootstrap & autoloader
    ├── Provider/
    │   ├── GroqProvider.php               # AI Client provider
    │   └── GroqProviderAvailability.php   # API key check
    ├── Models/
    │   └── GroqTextGenerationModel.php    # OpenAI-compatible model
    ├── Metadata/
    │   └── GroqModelMetadataDirectory.php # Model capabilities
    └── Settings/
        └── GroqSettings.php               # Admin settings page
```

---

## FAQ

**Do I need a paid Groq account?**
No. Groq offers a free tier with generous rate limits. See [groq.com/pricing](https://groq.com/pricing).

**Where do I enter my API key?**
In **Settings → Connectors**, not in the Groq AI settings page. All API keys are managed centrally there.

**Will this work with third-party plugins that use the WordPress AI Client?**
Yes. Any plugin that calls `wp_ai_client_prompt()` or uses the AI Service will automatically use Groq.

**Can I use multiple AI providers at the same time?**
Yes. WordPress 7.0 supports multiple configured providers. Groq will be used first for text generation and fall back to the next provider for unsupported capabilities (e.g. image generation).

---

## Changelog

### 0.2.1
- Full integration with the PHP AI Client via `AbstractApiProvider`
- Added `wpai_preferred_text_models` filter so Groq is used first for text generation
- Settings page now shows only model selection (API key moved to Connectors Gallery)
- Autoloader for `src/` classes using PSR-4 convention
- Added `GroqProvider`, `GroqProviderAvailability`, `GroqTextGenerationModel`, `GroqModelMetadataDirectory`
- API key resolution priority: environment variable → PHP constant → WordPress option

### 2.0.0
- Rewrote registration to use the correct `wp_connectors_init` hook and `WP_Connector_Registry`
- Removed non-existent hooks
- Added model selection dropdown to settings page

### 1.1.5
- Initial release

---

## License

[GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html)

This plugin sends text prompts to the Groq API when WordPress AI features are used. Please review [Groq's privacy policy](https://groq.com/privacy-policy/) before use.
