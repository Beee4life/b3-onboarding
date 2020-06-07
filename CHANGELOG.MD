# B3 Onboarding Release Notes

## Introduction

This is a major update. I hadn't touched the plugin in a while, since it did what it needed to do, plus the lack of time and motivation didn't help.

Recently I've found my motivation back as well as a lot of time so I buckled down and crammed out v2.0.0.

There's a ton of new functions/options. Also there's now a website which documents all info. You can find it here (for now): https://b3onboarding.berryplasman.com.

I hope you like all the improvements. See the changelog below for all updates (I might have missed a few due to the large amount).

## Changelog 2.0.0

This is a major update. A ton of new features/functions/options have been added

#### These are the things that were added (in alphabetical order).

* [X] added constant for local development
* [X] added debug page
* [X] added debug widget
* [X] added default values for all (relevant) inputs
* [X] added example filters in a sub-folder
* [X] added global link color for emails
* [X] added menu icon in Wordpress menu
* [X] added more filters
    * [X] added filter to add custom fields to forms
    * [X] added filter to add hidden fields to forms
* [X] added more hooks
    * [X] added more hooks to add custom code to forms
* [X] added more user input options
* [X] added notification + counter if users need approval
* [X] added option to remove logo from email header
* [X] added option to add a global logo (for emails/default Wordpress forms)
* [X] added option to add a privacy box
* [X] added option to delete user account (through non WP forms)
* [X] added option to disable admin notification after user registration
* [X] added option to disable admin notification after user password change
* [X] added option to disable Wordpress' own pages
* [X] added option to remove the Wordpress admin bar for users who don't have admin access
* [X] added option to set a logo from the Wordpress media library
* [X] added option to set your own privacy text
* [X] added reCaptcha for registration/login pages (optional) 
* [X] added styling options for default Wordpress pages
* [X] added verification for all custom filter output
* [X] published an 'official' [website](https://b3onboarding.berryplasman.com) with
    * [X] documentation for
        * [X] all actions/hooks
        * [X] all filters
        * [X] all functions
    * [X] faqs
* [X] rewrote code (parts) to comply with [Wordpress Plugin Guidelines](https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/)
* [X] sanitized all inputs

#### These are the things that were changed.

* [X] changed the user approval page (in the admin) to show as a visible sub-page
* [X] removed the admin notification when manually adding a user
* [X] replaced deprecated contextual_help

#### Feedback

Any [feedback](https://github.com/Beee4life/b3-onboarding/issues) is welcome.
