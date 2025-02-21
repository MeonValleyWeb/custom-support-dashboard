<?php
/**
 * Plugin Name: Custom Contact Details
 * Plugin URI: 
 * Description: Displays contact information and provides a contact form
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
        add_shortcode('contact_details', array($this, 'display_contact_details'));
        add_shortcode('contact_form', array($this, 'display_contact_form'));
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
        
        if(isset($input['email']))
            $new_input['email'] = sanitize_email($input['email']);

        if(isset($input['phone']))
            $new_input['phone'] = sanitize_text_field($input['phone']);

        return $new_input;
    }

    public function print_section_info() {
        print 'Enter your contact information below:';
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

    public function display_contact_form() {
        ob_start();
        ?>
        <form id="custom-contact-form" class="contact-form" method="post">
            <p>
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </p>
            <p>
                <label for="message">Message:</label>
                <textarea name="message" id="message" required></textarea>
            </p>
            <p>
                <input type="submit" value="Send Message">
            </p>
            <?php wp_nonce_field('custom_contact_form_nonce'); ?>
        </form>
        <?php
        return ob_get_clean();
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