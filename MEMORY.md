# MEMORY - custom-support-dashboard

**Project:** Custom Support Dashboard  
**Type:** WordPress plugin  
**Author:** Andrew Wilkinson  
**Email:** andrew@meonvalleyweb.com

## Purpose
Replace default WordPress dashboard with branded support info and admin notices.

## For Bedrock
Install to `web/app/plugins/`

## Configuration Options

### Option 1: wp-config.php
```php
define('SUPPORT_COMPANY_NAME', 'Your Company');
define('SUPPORT_LOGO_URL', '/app/themes/your-theme/images/logo.png');
define('SUPPORT_EMAIL', 'your@email.com');
define('SUPPORT_PHONE', '+1234567890');
define('SUPPORT_WEBSITE', 'https://yourwebsite.com');
```

### Option 2: Bedrock config + .env
```php
// config/application.php
Config::define('SUPPORT_COMPANY_NAME', env('SUPPORT_COMPANY_NAME'));
```

```
# .env
SUPPORT_COMPANY_NAME='Your Company'
```

## Features
- Support info widget (logo, contact details)
- Admin notices widget (5 recent)
- Custom post type for notices
- "Admin Notices" sidebar menu

## Files
- `class-supportdashboard.php` — Main class
- `css/` — Styles
- `composer.json` — Package definition
- `phpcs.xml.dist` — Coding standards
