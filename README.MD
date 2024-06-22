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

You can customise every aspect of the onboarding process, such as control the look and feel of (almost) every email sent out by Wordpress.

It even offers features which Wordpress doesn't even have, such as approving (or reject) a user before they're activated. And there are a ton of other things, which you can set through the admin pages or with the help of [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks).

**What 'problems' does this plugin solve ?**

No more default Wordpress forms/emails. You now have full control over them.

This plugin gives you all the freedom to change registration forms/emails. Also you can extend this plugin in A LOT of ways, in terms of styling, output and other things.

<a name="version"></a>
### Version
3.11.0 - released 02.10.23

<a name="features"></a>
### Features

* override the following forms/pages
  * register
  * login
  * log out
  * lost password
  * reset password
  * account
* easily add custom fields to registration form
* change various settings on the fly with the use of hooks/filters
* (mostly) styled using [BEM](https://en.bem.info) methodology

<a name="installation"></a>
### Installation

1. Download the [latest release](https://github.com/Beee4life/b3-onboarding/archive/master.zip).
1. Copy the `b3-onboarding` folder into your `wp-content/plugins` folder.
1. Activate the `B3 OnBoarding` plugin via the plugins admin page.
1. (optional) Change any setting you want.

If you use a composer file to add any plugins/libraries. Add the following repository to your composer.json:

```
"repositories": [
  {
    "type":    "package",
    "package": {
      "name":    "Beee4life/b3-onboarding",
      "type":    "wordpress-plugin",
      "version": "3.11.0",
      "dist":    {
        "type": "zip",
        "url":  "https://github.com/Beee4life/b3-onboarding/archive/master.zip"
      }
    }
  },
]
```

Then run `composer require beee4life/b3-onboarding`

or add this to the require section by hand:

```
"beee4life/b3-onboarding": "3.11.0",
```

and run `composer update`.

<a name="activate"></a>
### Activation

If you activate this plugin the following will be done:
* the necessary pages are created (if they're not there already)
* default setting/limits are set
* 2 new user roles are created: b3_approval, b3_activation
* access to admin is restricted for subscribers

<a name="shortcodes"></a>
### Shortcodes
* [account-page] = renders the account page
* [lostpass-form] = renders the lost password form
* [login-form] = renders the login form
* [register-form] = renders the regster form
* [resetpass-form] = renders the reset password form
* [user-management] = renders the user management page

<a name="hooks"></a>
### Actions

There are a lot of actions which you can use, to hook your code to. There are too many to list here, so find them on the [official website](https://b3onboarding.berryplasman.com/actions/).

<a name="filters"></a>
### Filters

There are a lot of filters which you can use. There are too many to list here (50+), so find them on the [official website](https://b3onboarding.berryplasman.com/filters/).

If you use hooks, you have more settings available than if you (just) use the admin.
Some functions are not (yet) available through the admin pages and some will never be.

<a name="uninstall"></a>
### Uninstall

1. All stored options are removed.
2. The plugin is removed.

Starting with v3.1.0 there's now a setting to prevent deletion of all data/settings.

<a name="disclaimer"></a>
### Disclaimer
1. Not 100% ready for multisite yet, but almost. Not all emails are 'caught' yet.
1. Not tested with Gutenberg, not even once, but I have good hopes it will work.

<a name="roadmap"></a>
### Roadmap
These are the things we have planned to add.
* [ ] add more security stuff
* [ ] clean-up settings pages
* [ ] remove `<br>` from email templates
* [ ] create option to send a preview email
* [ ] add front-end management to multisite as well
* [ ] multi-page registration forms
* [ ] integration with Woocommerce (emails)
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber

If you have any suggestions/requests, please [read this](https://github.com/Beee4life/b3-onboarding/wiki/Feature-request).

<a name="changelog"></a>
3.11.0 - xx.06.24
* add magic link login option (single site only, for now)
* added new filters
* * b3_otp_email()
* * b3_otp_time_out()
* * b3_password_special_chars()
* * b3_password_extra_special_chars()
* * b3_disallowed_domains()
* added blocking of disallowed user names/domain names

3.10.0 - 02.10.23
* filter out nav menu items
* sprintf admin tabs
* honour redirect_to on logout
* array refactoring `array()` to `[]`

3.9.0 - 11.01.23
* remove jquery override

3.8.2 - 28.07.22
* change to correct versions

3.8.1 - 28.07.22
* add proper readmes

* 3.8.0 - 28.07.22
* fix non-working contextual help tabs
* load admin scripts later so they don't conflict with `wp-themeplugin-editor`
* remove new lines in default messages
* added more filters for multisite
* safer downloading of example files

3.7.0 - 17.04.22
* added [codemirror](https://codemirror.net/) for email template and styling
* added new admin tab for email template and styling
* add filters to override styling (but not input) for several emails
* move link color field to settings tab
* conditionally load admin.js

3.6.0 - 05.04.22
* fix incorrect field name login
* don't autoload options
* output image in admin (if set by filter)
* hide more fields which have no meaning for the current registration type

3.5.0 - 03.04.22
* fix styling multisite registration forms
* add new localhost filters for multisite
* add recaptcha theme
* add/optimize templates
* update contextual help

3.4.0 - 31.03.22
* add new template: lostpassword/email
* add new filters
* improve localhost values in forms
* change notice text lost password form
* restrict link color to email content
* fix replace var %user_login%
* remove redundant new line in email template
* remove %site_url% from explanation (not used anymore)

3.3.0 - 29.03.22
* improved welcome page redirect
* get translated ID of privacy page if WPML is active

3.2.1 - 24.03.22
* fixed redirect after login for admins

3.2.0 - 24.03.22
* added development notice filter
* added option for a one-time welcome page
* added option for banned domains
* added color picker for link color
* added new hook before/after account page
* extended more filters to preview
* implemented checked/selected functions
* rebuild template files with more hooks/separate files

3.1.0 - 06.03.22
* improved multisite functions
* added honeypot option
* added reset to default option
* added option to 'prevent delete settings on uninstall'
* added status class to messages
* added new filters
* optimized existing filters
* optimized user templates
* reformatted debug info page
* is_localhost function

3.0 - 13.06.20
* added option to request access to create a site (multisite)
* added reCaptcha v3
* added user input for disallowed usernames
* a lot of MU stuff

For older changelogs, see [this link](https://b3onboarding.berryplasman.com/changelog/).
