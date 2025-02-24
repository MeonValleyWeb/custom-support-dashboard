# Custom Support Dashboard

A simple WordPress plugin for Bedrock installations that displays your support contact information on the admin dashboard along with customizable admin notices.

## Author
Andrew Wilkinson  
Email: andrew@meonvalleyweb.com  
Website: https://meonvalleyweb.com/plugins

## Description

This plugin creates a custom dashboard for WordPress admin that replaces the default WordPress dashboard widgets with:

1. **Support Information Widget** - Displays your logo, company name, website, email, and phone number in a clean, professional layout
2. **Admin Notices Widget** - Shows the 5 most recent admin notices with the ability to add and edit notices

## Installation

1. Clone this repository into your Bedrock WordPress installation:
   ```
   cd web/app/plugins/
   git clone https://github.com/yourusername/custom-support-dashboard.git
   ```

2. Activate the plugin through the WordPress admin interface

## Configuration

Add the following constants to your Bedrock configuration:

### Option 1: Add to wp-config.php
```php
// Support Dashboard Configuration
define('SUPPORT_COMPANY_NAME', 'Your Company Name');
define('SUPPORT_LOGO_URL', '/app/themes/your-theme/images/logo.png');
define('SUPPORT_EMAIL', 'your@email.com');
define('SUPPORT_PHONE', '+1234567890');
define('SUPPORT_WEBSITE', 'https://yourwebsite.com');
```

### Option 2: Add to Bedrock config files (Recommended)
Add to your `config/application.php` or create a new file `config/support.php`:

```php
<?php
// Support Dashboard Configuration
Config::define('SUPPORT_COMPANY_NAME', env('SUPPORT_COMPANY_NAME') ?: 'Your Company Name');
Config::define('SUPPORT_LOGO_URL', env('SUPPORT_LOGO_URL') ?: '/app/themes/your-theme/images/logo.png');
Config::define('SUPPORT_EMAIL', env('SUPPORT_EMAIL') ?: 'support@example.com');
Config::define('SUPPORT_PHONE', env('SUPPORT_PHONE') ?: '+1234567890');
Config::define('SUPPORT_WEBSITE', env('SUPPORT_WEBSITE') ?: 'https://example.com');
```

Then in your `.env` file:
```
SUPPORT_COMPANY_NAME='Your Company Name'
SUPPORT_LOGO_URL='/app/themes/your-theme/images/logo.png'
SUPPORT_EMAIL='your@email.com'
SUPPORT_PHONE='+1234567890'
SUPPORT_WEBSITE='https://yourwebsite.com'
```

## Usage

### Support Information
The Support Information widget displays automatically on the dashboard with the configured information.

### Admin Notices
To manage admin notices:

1. Go to "Admin Notices" in the WordPress admin sidebar
2. Click "Add New" to create a new notice
3. Enter a title and content for your notice
4. Publish the notice to make it appear on the dashboard

## Features

- Custom dashboard with responsive design
- Left-aligned logo with right-aligned contact details
- Custom post type for admin notices
- Dashboard widget to display recent notices
- Easy configuration via WordPress constants
- Lightweight and optimized for performance

## Development

### Structure
```
custom-support-dashboard/
├── custom-support-dashboard.php  # Main plugin file
└── README.md                     # This file
```

### Requirements
- WordPress 5.0 or higher
- Bedrock installation (recommended)

## License
GPL v2 or later

## Support
For support, please contact andrew@meonvalleyweb.com