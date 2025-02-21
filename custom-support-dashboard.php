<?php
/**
 * Plugin Name: Custom Support Dashboard
 * Description: Displays support contact information on WordPress dashboard
 * Version: 1.0.0
 * Author: Your Name
 */

namespace App\Plugins\SupportDashboard;

defined('ABSPATH') || exit;

class SupportDashboard {
    private $support_details;

    public function __construct() {
        // Remove default dashboard widgets
        add_action('wp_dashboard_setup', [$this, 'remove_default_dashboard_widgets'], 999);
        
        // Add our custom dashboard
        add_action('wp_dashboard_setup', [$this, 'add_custom_dashboard']);
        
        // Add custom styles
        add_action('admin_enqueue_scripts', [$this, 'add_dashboard_styles']);
        
        // Load support details from env
        $this->support_details = [
            'company_name' => env('SUPPORT_COMPANY_NAME'),
            'logo_path'    => env('SUPPORT_LOGO_PATH'),
            'email'        => env('SUPPORT_EMAIL'),
            'phone'        => env('SUPPORT_PHONE'),
            'website'      => env('SUPPORT_WEBSITE')
        ];
    }

    public function remove_default_dashboard_widgets() {
        global $wp_meta_boxes;
        
        // Remove welcome panel
        remove_action('welcome_panel', 'wp_welcome_panel');
        
        // Remove default widgets
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
        unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    }

    public function add_custom_dashboard() {
        wp_add_dashboard_widget(
            'custom_support_dashboard',
            'Support Contact Information',
            [$this, 'render_dashboard']
        );
    }

    public function render_dashboard() {
        ?>
        <div class="support-dashboard">
            <?php if ($this->support_details['logo_path']): ?>
                <div class="support-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri() . $this->support_details['logo_path']); ?>" 
                         alt="<?php echo esc_attr($this->support_details['company_name']); ?> Logo">
                </div>
            <?php endif; ?>

            <div class="support-info">
                <?php if ($this->support_details['company_name']): ?>
                    <h2><?php echo esc_html($this->support_details['company_name']); ?></h2>
                <?php endif; ?>

                <div class="support-grid">
                    <?php if ($this->support_details['website']): ?>
                        <div class="support-item">
                            <span class="dashicons dashicons-admin-site"></span>
                            <a href="<?php echo esc_url($this->support_details['website']); ?>" 
                               target="_blank" rel="noopener">
                                <?php echo esc_html($this->support_details['website']); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->support_details['email']): ?>
                        <div class="support-item">
                            <span class="dashicons dashicons-email"></span>
                            <a href="mailto:<?php echo esc_attr($this->support_details['email']); ?>">
                                <?php echo esc_html($this->support_details['email']); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->support_details['phone']): ?>
                        <div class="support-item">
                            <span class="dashicons dashicons-phone"></span>
                            <a href="tel:<?php echo esc_attr($this->support_details['phone']); ?>">
                                <?php echo esc_html($this->support_details['phone']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    public function add_dashboard_styles() {
        $screen = get_current_screen();
        if ($screen->id !== 'dashboard') {
            return;
        }

        wp_add_inline_style('dashicons', '
            .support-dashboard {
                padding: 20px;
                background: #fff;
                border-radius: 4px;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }

            .support-logo {
                text-align: center;
                margin-bottom: 20px;
            }

            .support-logo img {
                max-width: 200px;
                height: auto;
            }

            .support-info h2 {
                margin: 0 0 20px;
                padding: 0;
                text-align: center;
                color: #1d2327;
            }

            .support-grid {
                display: grid;
                gap: 15px;
            }

            .support-item {
                display: flex;
                align-items: center;
                padding: 10px;
                background: #f8f9fa;
                border-radius: 4px;
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

            #dashboard-widgets .postbox {
                padding-top: 0;
            }

            #dashboard-widgets .postbox h2 {
                padding: 12px;
                border-bottom: 1px solid #dcdcde;
            }
        ');
    }
}

// Initialize the plugin
add_action('plugins_loaded', function() {
    new SupportDashboard();
});