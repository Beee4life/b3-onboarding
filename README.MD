# B3 Onboarding

Version: 1.0.5

This plugin focusses on how to 'onboard' (in other words register/add) users to your site in the smoothest way possible.

You can easily customise a lot of things, if you know your way around [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks). Also you can use the admin pages.

Note: we're still testing. Everything should be working, but this doesn't mean it's 100% bug free yet.

## Contents

- [Activation](#activate)
- [Deactivation](#deactivate)
- [Uninstall](#uninstall)
- [Features](#features)
- [Hooks](#hooks)
- [Filters](#filters)
- [Shortcodes](#shortcodes)
- [Notes](#notes)
- [Roadmap](#roadmap)
- [Changelog](#changelog)

<a name="activate"></a>
### Activation 

If you activate this plugin the following will be done:
* the necessary pages are created
* default setting/limits are set
* 2 new user roles are created: b3_approval, b3_activation
* access to admin is restricted for some user roles

<a name="deactivate"></a>
### Deactivation 

All option values are removed as well as both user roles, created by B3. 

<a name="uninstall"></a>
### Uninstall

All stored options are removed (unless option to not remove data is selected) and the plugin is removed.

<a name="features"></a>
### Features

* custom forms/pages
  * register
  * login
  * forgot password
  * reset password
  * account
* (mostly) styled according to [BEM](https://en.bem.info) procedure
* add custom fields through hooks
* WPML prepared (partially)
* Multisite prepared (partially)


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

<a name="shortcodes"></a>
### Shortcodes
* [account-page] = renders account page
* [forgotpass-form] = renders forgot password form
* [login-form] = renders login form
* [register-form] = renders regster form
* [resetpass-form] = renders reset password form
* [user-management] = renders user management page

<a name="notes"></a>
### Notes
1. It's still under development, use at your own risk.
1. Not styled for mobile yet.
1. The link in the reset password email, is not 'custom' yet.

<a name="roadmap"></a>
### Roadmap
These are the things we have planned to add.
* [ ] preset more html emails
* [ ] make it more responsive
* [ ] reCAPTCHA
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber
* [ ] multi-page registration forms

#### What problems does this plugin solve ?

This plugin gives you all the freedom to change/use different parts, without being forced to use other features or where pages are created which you don't need. You can extend this plugin in any way you want with filters/hooks or through a settings page.

<a name="changelog"></a>
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
