## CHANGELOG

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
