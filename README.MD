# B3 Onboarding

## Contents

- [Introduction](#introduction)
- [Version](#version)
- [Installation](#installation)
- [Activation](#activate)
- [Features](#features)
- [Shortcodes](#shortcodes)
- [Hooks](#hooks)
- [Filters](#filters)
- [Uninstall](#uninstall)
- [Disclaimer](#disclaimer)
- [Roadmap](#roadmap)
- [Changelog](#changelog)

<a name="introduction"></a>
### Introduction

This plugin focusses on how to 'onboard' (in other words register/add) users to your site in the smoothest way possible.

You can customise a lot of things, with the help of [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks) or you can use the admin pages.

**What problems does this plugin solve ?**

This plugin gives you all the freedom to change/use different parts, without being forced to use other features or where pages are created which you don't need. You can extend this plugin in any way you want with filters/hooks or through a settings page.

<a name="version"></a>
### Version
1.0.7 - released xx.xx.20

<a name="installation"></a>
### Installation

1. Download the [latest release](https://github.com/Beee4life/b3-onboarding/archive/master.zip).
1. Copy the `b3-onboarding` folder into your `wp-content/plugins` folder.
1. Activate the `B3 Onboarding` plugin via the plugins admin page.
1. (optional) Change default settings.

<a name="activate"></a>
### Activation 

If you activate this plugin the following will be done:
* the necessary pages are created
* default setting/limits are set
* 2 new user roles are created: b3_approval, b3_activation
* access to admin is restricted for subscribers (can be changed)

<a name="features"></a>
### Features

* custom forms/pages
  * register
  * login
  * forgot password
  * reset password
  * account
* (mostly) styled using [BEM](https://en.bem.info) methodology
* add custom fields through hooks

<a name="shortcodes"></a>
### Shortcodes
* [account-page] = renders account page
* [forgotpass-form] = renders forgot password form
* [login-form] = renders login form
* [register-form] = renders regster form
* [resetpass-form] = renders reset password form
* [user-management] = renders user management page

<a name="hooks"></a>
### Hooks

* b3_add_custom_fields_registration
* b3_after_insert_user
* b3_after_recaptcha_{form_type} (not used yet)
* b3_after_{template_name}
* b3_before_recaptcha_{form_type} (not used yet)
* b3_before_{template_name}
* b3_do_after_request_access
* b3_do_before_registration_form
* b3_do_before_request_access
* b3_new_user_activated
* b3_new_user_activated_by_admin
* b3_new_user_rejected

... there are more, we still need to index them all properly

<a name="filters"></a>
### Filters

* b3_filter_hidden_fields_values
* b3_do_filter_hidden_fields_values
* b3_add_filter_extra_fields_values
* b3_filter_closed_message
* b3_welcome_user_subject
* b3_welcome_user_message

... there are more, we still need to index them all properly

<a name="uninstall"></a>
### Uninstall

1. All stored options are removed (unless option to not remove data is selected).
1. The plugin is removed.

<a name="disclaimer"></a>
### Disclaimer
1. Not entirely ready for multisite yet
1. It's still under development, use at your own risk.
1. Not styled for mobile yet.
1. The link in the reset password email, is not 'custom' yet.
1. site owner receives new notification for approval when added manually 

<a name="roadmap"></a>
### Roadmap
These are the things we have planned to add.
* [ ] make it work with Multisite
* [ ] add more email sending methods
* [ ] preset more html emails
* [ ] make it more responsive
* [ ] add reCAPTCHA
* [ ] multi-page registration forms
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber

<a name="changelog"></a>
1.0.7
* replaced deprecated contextual_help

1.0.6
* fix incorrect fallback link for forgot password

1.0.5
* fix broken user ID on dashboard
* update css
* add redirect to login form

1.0.4
* protect function in uninstall.php
* always return html as content type
* remove table from login form

1.0.3
* update version

1.0.2
* remove log errors

1.0.1
* change password reset link in password reset email
* create function to retrieve password reset url/page/link
 
1.0.0
* version change to (hopefully) avoid composer issues
* clean up of unused code
* clean up of commented code

0.9-beta
* add more email settings
* clean up actions/filters
* refactor registration form
* exclude more user roles from admin
* remove fallback for existing pages
* output b3_* option values on debug page
* add dashboard widget

0.8-beta
* add first/last name to request access
* option to make first/last name required 

0.7-beta
* first decent release
