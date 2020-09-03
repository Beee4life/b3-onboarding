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
2.3.0 - released 03.09.20

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
### Actions

There are a lot of actions which you can use, to hook your code to. There are too many to list here, so find them on the [official website](https://b3onboarding.berryplasman.com/actions/).

<a name="filters"></a>
### Filters

There are a lot of filters which you can use. There are too many to list here, so find them on the [official website](https://b3onboarding.berryplasman.com/filters/).

If you use hooks, you have more settings available than if you (just) use the admin.
Some functions are not (yet) available through the admin pages and some will never be.

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
* [X] use custom passwords
* [X] disallow certain user names
* [X] validate extra fields added through a filter
* [ ] make it work with Multisite
* [ ] make it work with WPML
* [ ] add more security stuff
* [ ] add code editor to styling/template inputs
* [ ] look more into responsiveness
* [ ] multi-page registration forms
* [ ] style all default WordPress mails
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber

If you have any suggestions/requests, please [read this](https://github.com/Beee4life/b3-onboarding/wiki/Feature-request).

<a name="changelog"></a>
2.3.0
* custom passwords
* filter to override email changed notification email

2.2.0
* fix for undefined headers when manually adding a user with notification

2.1.1
* forgot to change version

2.1.0
* added validation for custom fields
* added reserved usernames
* added an option to use email only for registration
* added 'protection' for sub-pages of account page
* added better css for smaller screens

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
