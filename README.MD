# B3 Onboarding plugin

Version: 0.7-beta

This plugin focusses on how to 'onboard' (in other words register/add) users to your site in the smoothest way possible.

You can easily customise a lot of things, if you know your way around [actions/filters](https://codex.wordpress.org/Plugin_API/Hooks). Right now we focus mostly on technique, to make this plugin as customisable as possible. A proper user interface for the less techy will follow later.


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

<a name="activate"></a>
### Activation 

If you activate this plugin only a few most used toolbar settings are preset, because if you use this plugin, you want to limit something anyway ;)

<a name="deactivate"></a>
### Deactivation 

Nothing yet... but all settings will be removed and it leaves all custom data 'untouched'. 

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
* styled according to [BEM](https://en.bem.info) technology
* add custom fields through hooks
* WPML prepared (partially)
* Multisite prepared (partially)


<a name="hooks"></a>
### Hooks

* b3_add_custom_fields_registration
* b3_after_recaptcha_{form_type}
* b3_after_registration_form
* b3_before_recaptcha_{form_type}
* b3_before_submit_registration_form
* b3_do_before_request_access
* b3_do_after_request_access
* b3_do_before_registration_form
* b3_do_after_registration_form
* b3_new_user_activated
* b3_new_user_activated_by_admin
* b3_before_recaptcha_{$form_type}
* b3_after_recaptcha_{$form_type}

<a name="filters"></a>
### Filters

* b3_filter_hidden_fields_values
* b3_do_filter_hidden_fields_values
* b3_add_filter_extra_fields_values
* b3_filter_closed_message
* b3_welcome_user_subject
* b3_welcome_user_message

<a name="shortcodes"></a>
### Shortcodes
* [account-page] = renders account page
* [forgotpass-form] = renders forgot password form
* [login-form] = renders login form
* [register-form] = renders regster form
* [resetpass-form] = renders reset password form

<a name="notes"></a>
### Notes
1. It's still under development, use at your own risk.
1. Not styled for mobile yet.

<a name="roadmap"></a>
### Roadmap
These are the things we have planned to add.
* [ ] integration with Salesforce
* [ ] integration with Mailchimp
* [ ] integration with AWeber
* [ ] multi page registration forms

#### What problems does this plugin solve ?

This plugin gives you all the freedom to change/use different parts, without being forced to use other features or where pages are created which you don't need. You can extend this plugin in any way you want with filters/hooks or through a settings page.
