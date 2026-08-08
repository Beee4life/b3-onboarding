## CHANGELOG

3.19.0
* improve download of default template/css
* improve message above login on magic link + password option
* disable filtered input settings
* add admin users tab for multisite
* fix magic link for multisite
* added a lot of improvements for WPMU and user approval

3.18.0
* add option to send test email
* re-activated login in popup option
* improved form messages
* added new filters
  * b3_message_above_magic_link
  * b3_show_first_last_name_account
* changed meta keys to reflect their function better
  * b3_activate_login_popup

3.17.0
* added option to choose magic link or password
* changed tab 'Template' to 'Styling'
* added new input boxes
  * magic link message
  * magic link subject
* added new filters
  * b3_magic_link_message
  * b3_magic_link_subject

3.16.0
* added email replacement vars filter, so you can add previews for your own emails
* changed b3_get_user_approval_link() to b3_get_user_approval_url()
* changed meta keys to reflect their function better
  * b3_activate_debug_info
  * b3_activate_domain_restriction
  * b3_activate_front_end_approval
  * b3_activate_honeypot
  * b3_activate_privacy_page
  * b3_activate_username_restriction
* added new filters
  * b3_activate_debug_info
  * b3_preview_email_message
  * b3_preview_email_subject
  * b3_replacement_vars
* removed b3_redirect_after_register filter

3.15.1
* fix incorrect option key to set password on registration

3.15.0
* improve approval + email confirmation
* escape and sanitize a lot
* add nonces to forms

3.14.0
* improve emails
* optimize otp functions
* optimize use of repeated wp functions
* add language on signup

3.13.0
* change version everywhere

3.12.2
* change composer file

3.12.1
* change composer version

3.12.0
* change 'code' argument/var to 'otpcode' to avoid conflicts

3.11.1 - 15.09.24
* fix forgotten creation of dynamic property in shortcodes file

3.11.0 - 07.09.24
* add magic link login option (single site only, for now)
* improved blocking of disallowed usernames/domain names
* moved logo field to emails tab
* added indicator for filter verification
* added new filters
* * b3_magic_link_email()
* * b3_magic_link_time_out()
* * b3_password_special_chars()
* * b3_password_extra_special_chars()
* * b3_disallowed_domains()
* * b3_show_email_widget()
* * b3_message_above_magiclink()
* * b3_email_intro()
* * b3_message_above_request_site()
* change settings to non-static method

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
~~~~~~~~
