<?php

namespace Haosf_Social_Proof_Toaster;

class Plugin {
	/**
	 * @var Plugin
	 */
	private static $instance;

	public static function autoload() {
		spl_autoload_register(function($class) {
			if(strpos( $class, 'Haosf_Social_Proof_Toaster') === 0) {
				$class = str_replace( '_', '-', $class);
				$class = str_replace( 'Haosf-Social-Proof-Toaster\\', '', $class);
				$class = strtolower( $class);
				require_once "class-$class.php";
			}
		});
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init_hooks() {
		add_action( 'wp_footer', [ $this, 'render_hidden_toasts' ], 0 );
	}

	public function render_hidden_toasts() {
		$toasts = $this->get_toasts();
		$html   = <<<HTML
<div id="haosf_toasts_wrapper">
    <div id="haosf_toasts_container" class="">
        <div>
            $toasts
        </div>
    </div>
</div>
HTML;
		echo $html;
	}


	public function enqueue_styles() {
		wp_enqueue_style( 'haosf-social-proof-toaster-main-css', HAOSF_ASSETS_URL . 'css/haosf-main.css' );
	}

	private function get_toasts() {
		$toasts_html = '';
		if ( is_product() and wc_get_product() instanceof \WC_Product) {
			$toast_html        = new Order_Count_Product_Social_Proof_Toast(wc_get_product() );
			$toasts_html    .= $toast_html;
		}
		if(empty($toasts_html)) {
			return '';
		}

		return $toasts_html;

	}
}
