=== B3 Onboarding ===
Contributors: Beee
Tags: user, management, registration, login, lost password, reset password, account
Requires at least: 4.3
Tested up to: 5.9.2
Requires PHP: 5.6
Stable tag: 3.2.1
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
