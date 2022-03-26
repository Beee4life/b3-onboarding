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

You can customise every aspect of the onboarding process, such as control the look and feel of (almost) evvery email sent out by Wordpress.

It even offers features which Wordpress doesn't even have, such as approving (or reject) a user before they're activated. And there are a ton of other things, which you can set through the admin pages or with the help of [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks).

**What 'problems' does this plugin solve ?**

No more default Wordpress forms/emails. You now have full control over them.

This plugin gives you all the freedom to change registration forms/emails. Also you can extend this plugin in A LOT of ways, in terms of styling, output and other things.

<a name="version"></a>
### Version
3.3.0 - released xx.03.21

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
      "version": "3.2.1",
      "dist":    {
        "type": "zip",
        "url":  "https://github.com/Beee4life/b3-onboarding/archive/master.zip"
      }
    }
  },
]
```

Then run composer require "beee4life/b3-onboarding"

or add this to the require section by hand:

```
"beee4life/b3-onboarding": "3.2.1",
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
* [ ] add code editor for styling/template inputs
* [ ] add filters for template html + css
* [ ] remove `<br />` from email templates
* [ ] create option to send a preview email
* [ ] add front-end management to multisite as well
* [ ] multi-page registration forms
* [ ] integration with Woocommerce (emails)
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber

If you have any suggestions/requests, please [read this](https://github.com/Beee4life/b3-onboarding/wiki/Feature-request).

<a name="changelog"></a>
3.3.0 - xx.03.22
* improved welcome page redirect

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

3.0 - 13.06.20
* added option to request access to create a site (multisite)
* added reCaptcha v3
* added user input for disallowed usernames
* a lot of MU stuff

2.5.0 - 03.11.20
* improve html/css for account page
* improve protection of pages
* improve requested redirect url
* encode redirect_to url when redirecting
* changed action name b3_after_user_activated_by_admin to b3_approve_user
* changed action name b3_new_user_rejected_by_admin to b3_before_reject_user
* added IF in account approved message for custom passwords
* removed hook on 'delete_user' and moved to custom hook on b3_before_reject_user
* added new filters to override registration (confirmation) messages
* added option to manually activate user
* added option to disable password changed email (for user)
* added option to register with email only

2.4.0 - 15.09.20
* fix for preventing login when email/user is not confirmed yet
* update css

2.3.0 - 03.09.20
* custom passwords
* filter to override email changed notification email

2.2.0 - 23.07.20
* fix for undefined headers when manually adding a user with notification

2.1.1 - 10.07.20
* forgot to change version

2.1.0 - 09.07.20
* added validation for custom fields
* added reserved usernames
* added an option to use email only for registration
* added 'protection' for sub-pages of account page
* added better css for smaller screens

2.0.3 - 17.06.20
* Removed beta notice stuff

2.0.2 - 17.06.20
* Forgot to properly change version number

2.0.1 - 16.06.20
* fixed incorrect redirect
* change user approval cap to promote users
* always return admin approval page in toolbar
* add forgotten meta keys for plugin delete

2.0.0 - 16.07.20
* replaced deprecated contextual_help
* escape user input for storage
* more emails to set
* style default WordPress login page
* force use own pages
* added recaptcha (optional) for registration/login pages
* added option to delete user account
* added option to add/change/remove logo from email header

Plus A LOT more - Read the [release notes](https://github.com/Beee4life/b3-onboarding/blob/master/CHANGELOG.MD).

1.0.6 - 03.12.19
* fix incorrect fallback link for lost password

1.0.5 - 28.03.19
* fix broken user ID on dashboard
* update css
* add redirect to login form

1.0.4 - 27.03.19
* protect function in uninstall.php
* always return html as content type
* remove table from login form

1.0.3 - 19.03.19
* update version

1.0.2 - 19.03.19
* remove log errors

1.0.1 - 19.03.19
* change password reset link in password reset email
* create function to retrieve password reset url/page/link

1.0.0 - 17.03.19
* version change to (hopefully) avoid composer issues
* clean up of unused code
* clean up of commented code

0.9-beta - 17.03.19
* add more email settings
* clean up actions/filters
* refactor registration form
* exclude more user roles from admin
* remove fallback for existing pages
* output b3_* option values on debug page
* add dashboard widget

0.8-beta - 14.03.19
* add first/last name to request access
* option to make first/last name required

0.7-beta - 04.03.19
* first decent release
