=== B3 Onboarding ===
Contributors: Beee
Tags: user, management, registration, login, lost password, reset password, account
Requires at least: 4.3
Tested up to: 6.3.1
Requires PHP: 5.6
Stable tag: 3.11.0
License: GNU v3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin styles the default WordPress pages into your own design. It gives you full control over the registration/login process (aka onboarding).

== Description ==

This plugin was built to 'onboard' (in other words register/add) users to your site in the smoothest way possible.

You can customise every aspect of the onboarding process, such as control the look and feel of any email sent out by the website.

There are a ton of things, which you can set through the admin pages or with the help of [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks)

== Installation ==

1. Download the [latest release](https://github.com/Beee4life/b3-onboarding/archive/master.zip).
1. Copy the `b3-onboarding` folder into your `wp-content/plugins` folder.
1. Activate the `B3 OnBoarding` plugin via the plugins admin page. If you run a multisite, it needs to be network activated.
1. (optional) Change any setting you want.

== Changelog ==
3.11.0
* locate template from function
* remove border width on submit button

3.10.0
* filter out nav menu items
* sprintf admin tabs
* honour redirect_to on logout
* array refactoring `array()` to `[]`

3.9.0
* remove jquery enqueue

3.8.2
* change to correct version numbers

3.8.1
* added missing readmes/versions

3.8.0
* fix non-working contextual help tabs
* load admin scripts later so they don't conflict with `wp-themeplugin-editor`
* remove new lines in default messages
* added more filters for multisite
* safer downloading of example files

3.7.0
* added [codemirror](https://codemirror.net/) for email template and styling
* added new admin tab for email template and styling
* add filters to style email content for "change admin email"
* move link color field to settings tab
* conditionally load admin.js

3.6.0
* fix incorrect field name login
* don't autoload options
* output image in admin (if set by filter)
* hide more fields which have no meaning for the current registration type

3.5.0
* fix styling multisite registration forms
* add new localhost filters for multisite
* add recaptcha theme
* add/optimize templates
* update contextual help

3.4.0
* add new template: lostpassword/email
* add new filters
* improve localhost values in forms
* change notice text lost password form
* 'limit' link color to email content
* fix replace var %user_login%
* remove redundant new line in email template
* remove %site_url% from explanation (not used anymore)

3.3.0
* improved welcome page redirect
* get translated ID of privacy page if WPML is active

3.2.1
* fixed redirect after login for admins

3.2.0
* added development notice filter
* added option for a one-time welcome page
* added option for banned domains
* added color picker for link color
* added new hook before/after account page
* extended more filters to preview
* implemented checked/selected functions
* rebuild template files with more hooks/separate files

3.1.0
* improved multisite functions
* added honeypot feature
* added 'prevent delete settings on uninstall' setting
* added new filters
* added status class to messages
* optimized existing filters
* optimized user templates
* reformatted debug info page

* 3.0
* added option to request access to create a site (multisite)
* added reCaptcha v3
* added user input for disallowed usernames
* alot of MU stuff

For older changelogs see the [website](https://b3onboarding.berryplasman.com).

== Update Notice ==
A ton of new features, so you'd better upgrade.
