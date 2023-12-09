<?php

namespace Yoco\Telemetry\Models;

use WP_Theme;
use Yoco\Gateway\Gateway;
use Yoco\Gateway\Provider;

use function Yoco\yoco;

class TelemetryObject {

	private ?string $name = null;

	private ?string $host = null;

	private ?string $url = null;

	private ?string $php = null;

	private ?string $wp = null;

	private ?string $wc = null;

	private ?string $yoco = null;

	private ?string $yocoMode = null;

	private ?array $theme = null;

	private ?array $themes = null;

	private ?array $plugins = null;

	public function getSiteName(): string {
		if ( null === $this->name ) {
			$this->name = $this->getDomain() . ' - ' . get_bloginfo( 'name' );
		}

		return $this->name;
	}

	public function getDomain(): string {
		if ( null === $this->host ) {
			$url = wp_parse_url( $this->getHostUrl() );

			$this->host = $url['host'];
		}

		return $this->host;
	}

	public function getHostUrl(): string {
		if ( null === $this->url ) {
			$this->url = get_bloginfo( 'url' );
		}

		return $this->url;
	}

	public function getPhpVersion(): string {
		if ( null === $this->php ) {
			$this->php = phpversion();
		}

		return $this->php;
	}

	public function getWpVersion(): string {
		if ( null === $this->wp ) {
			$this->wp = get_bloginfo( 'version' );
		}

		return $this->wp;
	}

	public function getWcVersion(): string {
		if ( null === $this->wc ) {
			$this->wc = defined( 'WC_VERSION' ) ? \WC_VERSION : '';
		}

		return $this->wc;
	}

	public function getYocoPluginVersion(): string {
		if ( null === $this->yoco ) {
			$this->yoco = defined( 'YOCO_PLUGIN_VERSION' ) ? YOCO_PLUGIN_VERSION : '';
		}

		return $this->yoco;
	}

	public function getYocoPluginMode(): string {
		$instance = yoco( Provider::class )->getInstance() ?? new Gateway();

		if ( null === $this->yocoMode && null !== $instance ) {
			$this->yocoMode = $instance->mode->getMode();
		}

		return $this->yocoMode;
	}

	public function getActiveThemeDetails(): array {
		if ( null === $this->theme ) {
			$theme = wp_get_theme();

			$this->theme = array(
				'name'    => $theme->exists() ? $theme['Name'] : '',
				'version' => $theme->exists() ? $theme['Version'] : '',
			);
		}

		return $this->theme;
	}

	public function getInstalledThemesDetails(): array {
		if ( null === $this->themes ) {
			$themes = wp_get_themes();

			$this->themes = array_map(
				fn ( WP_Theme $theme): array => array(
					'name'    => $theme['Name'],
					'version' => $theme['Version'],
				),
				array_values( $themes )
			);
		}

		return $this->themes;
	}

	public function getInstalledPluginsDetails(): array {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( null === $this->plugins ) {
			$plugins = get_plugins();

			$this->plugins = array_map(
				fn ( string $name, array $plugin): array => array(
					'name'    => $plugin['Name'],
					'version' => $plugin['Version'],
					'active'  => wc_bool_to_string( is_plugin_active( $name ) ),
				),
				array_keys( $plugins ),
				array_values( $plugins )
			);
		}

		return $this->plugins;
	}
}
