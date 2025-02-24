<?php
/**
 * Plugin Name: Custom Support Dashboard
 * Description: Displays support contact information and admin notices on WordPress dashboard
 * Version: 1.0.0
 * Plugin URI: https://meonvalleyweb.com/plugins/
 * Author: Andrew Wilkinson
 * Author URI: https://meonvalleyweb.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-support-dashboard
 * Requires PHP: 7.0
 *
 * @package MeonValleyWeb\CustomSupportDashboard
 */

namespace MeonValleyWeb\CustomSupportDashboard;

defined( 'ABSPATH' ) || exit;

/**
 * Main class for the Custom Support Dashboard plugin.
 *
 * @package MeonValleyWeb\CustomSupportDashboard
 * @since 1.0.0
 */
class SupportDashboard {
	/**
	 * Support information details.
	 *
	 * @var array
	 */
	private $support_details;

	/**
	 * Initialize the Support Dashboard.
	 *
	 * Defines constants, sets up support details and registers hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Define support details using constants.
		if ( ! defined( 'MEONVALLEYWEB_CSD_COMPANY_NAME' ) ) {
			define( 'MEONVALLEYWEB_CSD_COMPANY_NAME', 'Your Company Name' );
		}
		if ( ! defined( 'MEONVALLEYWEB_CSD_LOGO_URL' ) ) {
			define( 'MEONVALLEYWEB_CSD_LOGO_URL', '/app/themes/your-theme/images/logo.png' );
		}
		if ( ! defined( 'MEONVALLEYWEB_CSD_EMAIL' ) ) {
			define( 'MEONVALLEYWEB_CSD_EMAIL', 'support@example.com' );
		}
		if ( ! defined( 'MEONVALLEYWEB_CSD_PHONE' ) ) {
			define( 'MEONVALLEYWEB_CSD_PHONE', '+1234567890' );
		}
		if ( ! defined( 'MEONVALLEYWEB_CSD_WEBSITE' ) ) {
			define( 'MEONVALLEYWEB_CSD_WEBSITE', 'https://example.com' );
		}

		// Load support details from constants.
		$this->support_details = array(
			'company_name' => MEONVALLEYWEB_CSD_COMPANY_NAME,
			'logo_url'     => MEONVALLEYWEB_CSD_LOGO_URL,
			'email'        => MEONVALLEYWEB_CSD_EMAIL,
			'phone'        => MEONVALLEYWEB_CSD_PHONE,
			'website'      => MEONVALLEYWEB_CSD_WEBSITE,
		);

		// Remove default dashboard widgets.
		add_action( 'wp_dashboard_setup', array( $this, 'remove_default_dashboard_widgets' ), 999 );
		
		// Add our custom dashboard.
		add_action( 'wp_dashboard_setup', array( $this, 'add_custom_dashboard' ) );
		
		// Add custom styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'add_dashboard_styles' ) );
		
		// Add admin menu for notices.
		add_action( 'admin_menu', array( $this, 'add_admin_notices_menu' ) );
		
		// Register post type for admin notices.
		add_action( 'init', array( $this, 'register_admin_notices_post_type' ) );
	}

	/**
	 * Remove default WordPress dashboard widgets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function remove_default_dashboard_widgets() {
		global $wp_meta_boxes;

		// Remove welcome panel.
		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		
		// Remove default widgets.
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
		unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	}

	/**
	 * Add custom dashboard widgets.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_custom_dashboard() {
		wp_add_dashboard_widget(
			'custom_support_dashboard',
			'Support Information',
			array( $this, 'render_dashboard' )
		);

		wp_add_dashboard_widget(
			'custom_admin_notices',
			'Admin Notices',
			array( $this, 'render_admin_notices' )
		);
	}

	/**
	 * Register custom post type for admin notices.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function register_admin_notices_post_type() {
		$args = array(
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => false,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title', 'editor' ),
			'labels'             => array(
				'name'               => 'Admin Notices',
				'singular_name'      => 'Admin Notice',
				'add_new'            => 'Add New Notice',
				'add_new_item'       => 'Add New Admin Notice',
				'edit_item'          => 'Edit Admin Notice',
				'new_item'           => 'New Admin Notice',
				'view_item'          => 'View Admin Notice',
				'search_items'       => 'Search Admin Notices',
				'not_found'          => 'No admin notices found',
				'not_found_in_trash' => 'No admin notices found in Trash',
			),
		);

		register_post_type( 'admin_notice', $args );
	}

	/**
	 * Add admin menu for admin notices.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_admin_notices_menu() {
		add_menu_page(
			'Admin Notices',
			'Admin Notices',
			'manage_options',
			'edit.php?post_type=admin_notice',
			'',
			'dashicons-megaphone',
			30
		);
	}

	/**
	 * Render support dashboard widget content.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_dashboard() {
		?>
		<div class="support-dashboard">
			<div class="support-header">
				<?php if ( $this->support_details['logo_url'] ) : ?>
					<div class="support-logo">
						<img src="<?php echo esc_url( home_url( $this->support_details['logo_url'] ) ); ?>" 
							alt="<?php echo esc_attr( $this->support_details['company_name'] ); ?> Logo">
					</div>
				<?php endif; ?>

				<div class="support-contact">
					<?php if ( $this->support_details['company_name'] ) : ?>
						<h2><?php echo esc_html( $this->support_details['company_name'] ); ?></h2>
					<?php endif; ?>

					<div class="support-details">
						<?php if ( $this->support_details['website'] ) : ?>
							<div class="support-item">
								<span class="dashicons dashicons-admin-site"></span>
								<a href="<?php echo esc_url( $this->support_details['website'] ); ?>" 
									target="_blank" rel="noopener">
									<?php echo esc_html( $this->support_details['website'] ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( $this->support_details['email'] ) : ?>
							<div class="support-item">
								<span class="dashicons dashicons-email"></span>
								<a href="mailto:<?php echo esc_attr( $this->support_details['email'] ); ?>">
									<?php echo esc_html( $this->support_details['email'] ); ?>
								</a>
							</div>
						<?php endif; ?>

						<?php if ( $this->support_details['phone'] ) : ?>
							<div class="support-item">
								<span class="dashicons dashicons-phone"></span>
								<a href="tel:<?php echo esc_attr( $this->support_details['phone'] ); ?>">
									<?php echo esc_html( $this->support_details['phone'] ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render admin notices widget content.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_admin_notices() {
		$notices = get_posts(
			array(
				'post_type'      => 'admin_notice',
				'posts_per_page' => 5,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		if ( empty( $notices ) ) {
			echo '<p>No admin notices available.</p>';

			if ( current_user_can( 'manage_options' ) ) {
				echo '<p><a href="' . esc_url( admin_url( 'post-new.php?post_type=admin_notice' ) ) . '" class="button">Add New Notice</a></p>';
			}

			return;
		}
		?>
		<div class="admin-notices">
			<?php foreach ( $notices as $notice ) : ?>
				<div class="admin-notice">
					<h3><?php echo esc_html( $notice->post_title ); ?></h3>
					<div class="notice-content">
						<?php echo wp_kses_post( wpautop( $notice->post_content ) ); ?>
					</div>
					<div class="notice-meta">
						<span class="notice-date"><?php echo get_the_date( 'F j, Y', $notice ); ?></span>
						<?php if ( current_user_can( 'edit_post', $notice->ID ) ) : ?>
							<a href="<?php echo esc_url( get_edit_post_link( $notice->ID ) ); ?>" class="edit-notice">Edit</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			
			<?php if ( current_user_can( 'manage_options' ) ) : ?>
				<p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=admin_notice' ) ); ?>" class="button">View All Notices</a>
				<a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=admin_notice' ) ); ?>" class="button">Add New Notice</a></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Add custom dashboard styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function add_dashboard_styles() {
		$screen = get_current_screen();
		if ( 'dashboard' !== $screen->id ) {
			return;
		}

		wp_add_inline_style(
			'dashicons',
			'
            /* Support Dashboard Styles */
            .support-dashboard {
                padding: 10px;
                background: #fff;
            }
            
            .support-header {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
            }
            
            .support-logo {
                flex: 0 0 auto;
                margin-right: 30px;
            }
            
            .support-logo img {
                max-width: 150px;
                height: auto;
            }
            
            .support-contact {
                flex: 1 1 auto;
            }
            
            .support-contact h2 {
                margin: 0 0 10px 0;
                padding: 0;
                color: #1d2327;
            }
            
            .support-details {
                display: flex;
                flex-direction: column;
                gap: 15px;
                padding-top: 10px;
            }
            
            .support-item {
                display: flex;
                align-items: center;
                padding: 5px 0;
            }
            
            .support-item .dashicons {
                margin-right: 10px;
                color: #2271b1;
            }
            
            .support-item a {
                color: #2271b1;
                text-decoration: none;
                font-size: 14px;
            }
            
            .support-item a:hover {
                color: #135e96;
                text-decoration: underline;
            }
            
            /* Admin Notices Styles */
            .admin-notices {
                padding: 10px 0;
            }
            
            .admin-notice {
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #dcdcde;
            }
            
            .admin-notice:last-child {
                border-bottom: none;
                margin-bottom: 5px;
            }
            
            .admin-notice h3 {
                margin: 0 0 10px;
                padding: 0;
                font-size: 16px;
                color: #1d2327;
            }
            
            .notice-content {
                margin-bottom: 10px;
            }
            
            .notice-content p {
                margin: 0 0 10px;
                line-height: 1.5;
            }
            
            .notice-meta {
                font-size: 12px;
                color: #646970;
                display: flex;
                justify-content: space-between;
            }
            
            .edit-notice {
                color: #2271b1;
                text-decoration: none;
            }
            
            .edit-notice:hover {
                color: #135e96;
                text-decoration: underline;
            }
            
            /* Dashboard General Styles */
            #dashboard-widgets .postbox {
                padding-top: 0;
            }
            
            #dashboard-widgets .postbox h2 {
                padding: 12px;
                border-bottom: 1px solid #dcdcde;
            }
            
            /* Responsive adjustments */
            @media screen and (max-width: 782px) {
                .support-header {
                    flex-direction: column;
                    align-items: flex-start;
                }
                
                .support-logo {
                    margin: 0 0 20px 0;
                }
            }
        '
		);
	}
}

// Initialize the plugin.
add_action(
	'plugins_loaded',
	function () {
		new SupportDashboard();
	}
);