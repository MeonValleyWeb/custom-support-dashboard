# CHANGELOG_PROGRESS - custom-support-dashboard

**Cloned:** 2026-04-02  
**Status:** Active WordPress plugin for Bedrock

## Project Overview
Custom WordPress admin dashboard for Bedrock installs. Replaces default widgets with support info and admin notices.

## Tech
- **Type:** WordPress plugin
- **Requirements:** WordPress 5.0+, Bedrock recommended
- **License:** GPL v2+

## Features
1. **Support Information Widget** — Logo, company name, website, email, phone
2. **Admin Notices Widget** — 5 most recent notices with add/edit capability

## Configuration
Constants (set in `wp-config.php` or Bedrock config):
- `SUPPORT_COMPANY_NAME`
- `SUPPORT_LOGO_URL`
- `SUPPORT_EMAIL`
- `SUPPORT_PHONE`
- `SUPPORT_WEBSITE`

Or use `.env` with Bedrock's `config/application.php`

## Structure
- `class-supportdashboard.php` — Main plugin class
- `css/` — Stylesheets
- `composer.json` — Composer package definition

## Pending Tasks
- [ ] Verify Bedrock config integration works
- [ ] Check admin notice CRUD functionality
- [ ] Test responsive design on dashboard
- [ ] Review PHPCS coding standards compliance

## Recent Activity
- Logo URL updates in class-supportdashboard
- Restructured composer.json
- Added coding standards config (phpcs.xml.dist)

## Notes
- Custom post type: Admin Notices
- Dashboard widget for recent notices
- Contact: andrew@meonvalleyweb.com

---
*This file is managed by Jarvis. Last updated: 2026-04-02*
