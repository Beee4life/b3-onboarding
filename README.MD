# B3 User Registration plugin

This plugin focusses on how to 'onboard' (in other words register/add) users to your site.

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
- [Source(s)](#sources)

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

* custom forms
  * register
  * login
  * forgot password
  * reset password
* options
  * custom passwords by user
  * send password by mail
  * recaptcha
* styled according to [BEM](https://en.bem.info) technology
* add custom fields through hooks


<a name="hooks"></a>
### Hooks

* b3_add_custom_fields_registration
* b3_after_recaptcha_{form_type}
* b3_after_registration_form
* b3_before_recaptcha_{form_type}
* b3_before_submit_registration_form

<a name="filters"></a>
### Filters

- b3_filter_hidden_fields

#### Example 

```
function b3_my_hidden_fields() {

    return [
        'key1' => 'value1',
        'key2' => 'value2',
    ];
}
add_filter( 'b3_filter_hidden_fields', 'b3_my_hidden_fields' );
```

<a name="roadmap"></a>
### Roadmap
These are the things we have planned to add.
* integration with Salesforce
* integration with Mailchimp
* multi page registration forms


What problems does this plugin solve ?

No part is obligated. You can use different parts, without being forced to use other features or where pages are created which you don't need. You can extend this plugin in any way you want.

### Features
- WPML ready
- Multisite ready
