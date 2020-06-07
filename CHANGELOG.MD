# B3 Onboarding Release Notes

This is a major update. I hadn't touched the plugin in a while, since it did what it needed to do, plus the lack of time and motivation didn't help.

Recently I've found my motivation back as well as a lot of time so I buckled down and crammed out v2.0.0.

There's a ton of new hooks/filters/functions/settings/options. Also there's now a website which documents everything. You can find it on [https://b3onboarding.berryplasman.com][website] (for now).

I hope you like all the improvements. See the changelog below for all updates (I might have missed a few due to the large amount).

Any [feedback][github-issues] is welcome.

## Changelog 2.0.0

#### These are the things that were added (in alphabetical order)

**General goodies**

* [X] added default values for all (relevant) inputs
* [X] added a global link color for emails
* [X] added a menu icon in WordPress menu
* [X] added more user input options
* [X] added a notification + counter if users need approval
* [X] added an option to remove logo from email header
* [X] added an option to add a global logo (for emails/default WordPress forms)
* [X] added an option to add a privacy box
* [ ] added an option to delete user account (through non WP forms) - tbc
* [X] added an option to disable admin notification after user registration
* [X] added an option to disable admin notification after user password change
* [X] added an option to disable WordPress' own pages
* [X] added an option to remove the WordPress admin bar for users who don't have admin access
* [X] added an option to set a logo from the WordPress media library
* [X] added an option to set your own privacy text
* [X] added reCaptcha for registration/login pages (optional) 
* [X] added styling options for default WordPress pages
* [X] added a verification for all custom filter output
* [X] published an 'official' [website][website] with
    * [X] documentation for
        * [X] all actions/hooks
        * [X] all filters
        * [X] all functions
    * [X] faqs

**Developer goodies**

* [X] added a constant for local development, which 'unlocks' testing/development options
* [X] added a debug page
* [X] added a debug dashboard widget for quick access to email preview links
* [X] added example filters in a sub-folder
* [X] added more filters
    * [X] added filter to add custom fields to forms
    * [X] added filter to add hidden fields to forms
* [X] added more hooks
    * [X] added more hooks to add custom code to forms
* [X] rewrote code (parts) to comply with [WordPress Plugin Guidelines][guidelines]
* [X] sanitized all inputs

#### These are the things that were changed

* [X] changed the user approval page (in the admin) to show as a visible sub-page
* [X] removed the admin notification when manually adding a user
* [X] replaced deprecated contextual_help

[github-issues]: https://github.com/Beee4life/b3-onboarding/issues
[guidelines]: https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/
[website]: https://b3onboarding.berryplasman.com
