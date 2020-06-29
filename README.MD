# B3 OnBoarding

## Contents

- [Introduction](#introduction)
- [Version](#version)
- [Features](#features)
- [Installation](#installation)
- [Activation](#activate)
- [Shortcodes](#shortcodes)
- [Hooks](#hooks)
- [Filters](#filters)
- [Uninstall](#uninstall)
- [Disclaimer](#disclaimer)
- [Roadmap](#roadmap)
- [Changelog](#changelog)

<a name="introduction"></a>
### Introduction

This plugin was built to 'onboard' (in other words register/add) users to your site in the smoothest way possible.

You can customise every aspect of the onboarding process, such as control the look and feel of any email sent out by the website.

There are a ton of things, which you can set through the admin pages or with the help of [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks)

**What 'problems' does this plugin solve ?**

This plugin gives you all the freedom to change/use different parts, without being forced to use other features. You can extend this plugin in any way you want with filters/hooks or through a settings page.

Also you can get rid of those default Wordpress forms and use your own.

<a name="version"></a>
### Version
2.1.0 - released 30.06.20

<a name="features"></a>
### Features

* custom forms/pages
  * register
  * login
  * log out
  * lost password
  * reset password
  * account
* (mostly) styled using [BEM](https://en.bem.info) methodology
* change various settings on the fly with the use of hooks/filters

<a name="installation"></a>
### Installation

1. Download the [latest release](https://github.com/Beee4life/b3-onboarding/archive/master.zip).
1. Copy the `b3-onboarding` folder into your `wp-content/plugins` folder.
1. Activate the `B3 OnBoarding` plugin via the plugins admin page.
1. (optional) Change any setting you want.

<a name="activate"></a>
### Activation

If you activate this plugin the following will be done:
* the necessary pages are created
* default setting/limits are set
* 2 new user roles are created: b3_approval, b3_activation
* access to admin is restricted for subscribers

<a name="shortcodes"></a>
### Shortcodes
* [account-page] = renders account page
* [lostpass-form] = renders lost password form
* [login-form] = renders login form
* [register-form] = renders regster form
* [resetpass-form] = renders reset password form
* [user-management] = renders user management page

<a name="hooks"></a>
### Hooks

* b3_add_extra_fields_registration
* b3_after_insert_site
* b3_after_recaptcha_{$form_type}
* b3_after_recaptcha_register
* b3_before_recaptcha_{$form_type}
* b3_before_recaptcha_register
* b3_do_after_{$template_name}
* b3_do_after_submit_registration_form
* b3_do_before_{$template_name}
* b3_do_before_submit_registration_form
* b3_after_user_activated
* b3_after_user_activated_by_admin

These are just a few of all available filters which are available on the official website.

<a name="filters"></a>
### Filters

* b3_extra_fields
* b3_hidden_fields
* b3_registration_closed_message
* b3_welcome_user_subject
* b3_welcome_user_message

These are just a few of all available actions which are available on the official website.

If you use hooks, you have more settings available than if you use the admin.
Some functions are not (yet) available through the admin pages.

They will become available on the official website (soon).

<a name="uninstall"></a>
### Uninstall

1. All stored options are removed.
1. The plugin is removed.

<a name="disclaimer"></a>
### Disclaimer
1. Not entirely ready for multisite yet, we're working on it.
1. It's still under development, use at your own risk.
1. Not styled for mobile admin yet (might work, but no guarantees).
1. Not tested with Gutenberg.

<a name="roadmap"></a>
### Roadmap
These are the things we have planned to add.
* [ ] make it work with Multisite
* [ ] make it work with WPML
* [ ] add more security stuff
* [ ] add code editor to styling/template inputs
* [ ] disallow certain user names
* [ ] validate extra fields added through a filter
* [ ] look more into responsiveness
* [ ] multi-page registration forms
* [ ] style all default WordPress mails
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber

If you have any suggestions/requests, please [read this](https://github.com/Beee4life/b3-onboarding/wiki/Feature-request).

<a name="changelog"></a>
2.1.0
* added validation for custom fields
* added reserved usernames
* added an option to use email only for registration
* added 'protection' for sub-pages of account/profile

2.0.3
* Removed beta notice stuff

2.0.2
* Forgot to properly change version number

2.0.1
* fixed incorrect redirect
* change user approval cap to promote users
* always return admin approval page in toolbar
* add forgotten meta keys for plugin delete

2.0.0
* replaced deprecated contextual_help
* escape user input for storage
* more emails to set
* style default WordPress login page
* force use own pages
* added recaptcha (optional) for registration/login pages
* added option to delete user account
* added option to add/change/remove logo from email header

Plus A LOT more - Read the [release notes](https://github.com/Beee4life/b3-onboarding/blob/master/CHANGELOG.MD).

1.0.6 (03.12.19)
* fix incorrect fallback link for lost password

1.0.5 (28.03.19)
* fix broken user ID on dashboard
* update css
* add redirect to login form

1.0.4 (27.03.19)
* protect function in uninstall.php
* always return html as content type
* remove table from login form

1.0.3 (19.03.19)
* update version

1.0.2 (19.03.19)
* remove log errors

1.0.1 (19.03.19)
* change password reset link in password reset email
* create function to retrieve password reset url/page/link

1.0.0 (17.03.19)
* version change to (hopefully) avoid composer issues
* clean up of unused code
* clean up of commented code

0.9-beta (17.03.19)
* add more email settings
* clean up actions/filters
* refactor registration form
* exclude more user roles from admin
* remove fallback for existing pages
* output b3_* option values on debug page
* add dashboard widget

0.8-beta (14.03.19)
* add first/last name to request access
* option to make first/last name required

0.7-beta (04.03.19)
* first decent release
