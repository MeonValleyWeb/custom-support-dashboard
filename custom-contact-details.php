<?php
/**
 * Plugin Name: Custom Contact Details
 * Plugin URI: 
 * Description: Displays support contact information in dashboard and via shortcode
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: custom-contact-details
 */

// Prevent direct access to this file
defined('ABSPATH') || exit;

class CustomContactDetails {
    private $options;

    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_shortcode('contact_details', array($this, 'display_contact_details'));
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style('custom-contact-details', plugins_url('css/style.css', __FILE__));
        wp_enqueue_media(); // Enable media uploader for logo
    }

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'contact_details_widget',
            'Support Contact Information',
            array($this, 'display_dashboard_widget')
        );
    }

    public function display_dashboard_widget() {
        $options = get_option('contact_details_options');
        ?>
        <div class="support-contact-details">
            <?php if(!empty($options['logo_url'])): ?>
                <div class="support-logo">
                    <img src="<?php echo esc_url($options['logo_url']); ?>" alt="Support Logo">
                </div>
            <?php endif; ?>
            
            <div class="support-info">
                <?php if(!empty($options['company_name'])): ?>
                    <h3><?php echo esc_html($options['company_name']); ?></h3>
                <?php endif; ?>
                
                <?php if(!empty($options['website'])): ?>
                    <p><strong>Website:</strong> 
                        <a href="<?php echo esc_url($options['website']); ?>" target="_blank">
                            <?php echo esc_html($options['website']); ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <?php if(!empty($options['email'])): ?>
                    <p><strong>Email:</strong> 
                        <a href="mailto:<?php echo esc_attr($options['email']); ?>">
                            <?php echo esc_html($options['email']); ?>
                        </a>
                    </p>
                <?php endif; ?>
                
                <?php if(!empty($options['phone'])): ?>
                    <p><strong>Phone:</strong> 
                        <a href="tel:<?php echo esc_attr($options['phone']); ?>">
                            <?php echo esc_html($options['phone']); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    public function add_plugin_page() {
        add_options_page(
            'Contact Details Settings',
            'Contact Details',
            'manage_options',
            'contact-details-admin',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page() {
        $this->options = get_option('contact_details_options');
        ?>
        <div class="wrap">
            <h1>Contact Details Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('contact_details_group');
                do_settings_sections('contact-details-admin');
                submit_button();
                ?>
            </form>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#upload_logo_button').click(function(e) {
                e.preventDefault();
                var image = wp.media({ 
                    title: 'Upload Logo',
                    multiple: false
                }).open()
                .on('select', function(e){
                    var uploaded_image = image.state().get('selection').first();
                    var logo_url = uploaded_image.toJSON().url;
                    $('#logo_url').val(logo_url);
                    $('#logo_preview').attr('src', logo_url);
                });
            });
        });
        </script>
        <?php
    }

    public function page_init() {
        register_setting(
            'contact_details_group',
            'contact_details_options',
            array($this, 'sanitize')
        );

        add_settings_section(
            'contact_details_section',
            'Contact Information',
            array($this, 'print_section_info'),
            'contact-details-admin'
        );

        add_settings_field(
            'logo_url', 
            'Company Logo',
            array($this, 'logo_callback'),
            'contact-details-admin',
            'contact_details_section'
        );

        add_settings_field(
            'company_name',
            'Company Name',
            array($this, 'company_name_callback'),
            'contact-details-admin',
            'contact_details_section'
        );

        add_settings_field(
            'website',
            'Website URL',
            array($this, 'website_callback'),
            'contact-details-admin',
            'contact_details_section'
        );

        add_settings_field(
            'email',
            'Email Address',
            array($this, 'email_callback'),
            'contact-details-admin',
            'contact_details_section'
        );

        add_settings_field(
            'phone',
            'Phone Number',
            array($this, 'phone_callback'),
            'contact-details-admin',
            'contact_details_section'
        );
    }

    public function sanitize($input) {
        $new_input = array();
        
        if(isset($input['logo_url']))
            $new_input['logo_url'] = esc_url_raw($input['logo_url']);
            
        if(isset($input['company_name']))
            $new_input['company_name'] = sanitize_text_field($input['company_name']);
            
        if(isset($input['website']))
            $new_input['website'] = esc_url_raw($input['website']);
            
        if(isset($input['email']))
            $new_input['email'] = sanitize_email($input['email']);

        if(isset($input['phone']))
            $new_input['phone'] = sanitize_text_field($input['phone']);

        return $new_input;
    }

    public function print_section_info() {
        print 'Enter your contact information below:';
    }

    public function logo_callback() {
        printf(
            '<input type="text" id="logo_url" name="contact_details_options[logo_url]" value="%s" />
            <input type="button" id="upload_logo_button" class="button" value="Upload Logo" />
            <br><br>
            <img id="logo_preview" src="%s" style="max-width: 300px; display: %s;">',
            isset($this->options['logo_url']) ? esc_attr($this->options['logo_url']) : '',
            isset($this->options['logo_url']) ? esc_attr($this->options['logo_url']) : '',
            isset($this->options['logo_url']) ? 'block' : 'none'
        );
    }

    public function company_name_callback() {
        printf(
            '<input type="text" id="company_name" name="contact_details_options[company_name]" value="%s" />',
            isset($this->options['company_name']) ? esc_attr($this->options['company_name']) : ''
        );
    }

    public function website_callback() {
        printf(
            '<input type="url" id="website" name="contact_details_options[website]" value="%s" />',
            isset($this->options['website']) ? esc_url($this->options['website']) : ''
        );
    }

    public function email_callback() {
        printf(
            '<input type="email" id="email" name="contact_details_options[email]" value="%s" />',
            isset($this->options['email']) ? esc_attr($this->options['email']) : ''
        );
    }

    public function phone_callback() {
        printf(
            '<input type="tel" id="phone" name="contact_details_options[phone]" value="%s" />',
            isset($this->options['phone']) ? esc_attr($this->options['phone']) : ''
        );
    }

    public function display_contact_details() {
        $options = get_option('contact_details_options');
        $output = '<div class="contact-details">';
        
        if(!empty($options['logo_url'])) {
            $output .= '<div class="contact-logo">';
            $output .= '<img src="' . esc_url($options['logo_url']) . '" alt="Company Logo">';
            $output .= '</div>';
        }
        
        if(!empty($options['company_name'])) {
            $output .= '<h3>' . esc_html($options['company_name']) . '</h3>';
        }
        
        if(!empty($options['website'])) {
            $output .= '<p><strong>Website:</strong> ';
            $output .= '<a href="' . esc_url($options['website']) . '" target="_blank">';
            $output .= esc_html($options['website']) . '</a></p>';
        }
        
        if(!empty($options['email'])) {
            $output .= '<p><strong>Email:</strong> ';
            $output .= '<a href="mailto:' . esc_attr($options['email']) . '">';
            $output .= esc_html($options['email']) . '</a></p>';
        }
        
        if(!empty($options['phone'])) {
            $output .= '<p><strong>Phone:</strong> ';
            $output .= '<a href="tel:' . esc_attr($options['phone']) . '">';
            $output .= esc_html($options['phone']) . '</a></p>';
        }
        
        $output .= '</div>';
        return $output;
    }
}

if(class_exists('CustomContactDetails')) {
    $custom_contact_details = new CustomContactDetails();
}

// Activation hook
register_activation_hook(__FILE__, 'contact_details_activate');
function contact_details_activate() {
    // Add default options
    $default_options = array(
        'logo_url' => '',
        'company_name' => '',
        'website' => '',
        'email' => '',
        'phone' => ''
    );
    add_option('contact_details_options', $default_options);
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'contact_details_deactivate');
function contact_details_deactivate() {
    // Cleanup if needed
}