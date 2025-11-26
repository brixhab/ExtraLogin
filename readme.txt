=== ExtraLogin for HP ===
Contributors: Brixhab
Tags: hivepress, social login, facebook login, google login, authentication
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 1.0.0
Text Domain: extralogin-for-hp

Enhance your HivePress site with social authentication via Facebook and Google.

== Description ==

ExtraLogin for HP is a powerful extension for HivePress that enables social authentication on your WordPress site. Allow users to sign in seamlessly using their Facebook or Google accounts, reducing registration friction and improving user experience.

**Key Features:**

* **Facebook Authentication** - Let users sign in with their Facebook account
* **Google Authentication** - Enable Google Sign-In with the latest Google Identity Services API
* **Seamless Integration** - Works perfectly with HivePress login and registration forms
* **Easy Setup** - Simple configuration through HivePress settings
* **Automatic User Creation** - Creates WordPress user accounts automatically on first login
* **Secure** - Uses official OAuth APIs from Facebook and Google
* **Mobile Friendly** - Works great on all devices

**Requirements:**

* HivePress plugin (free version works)
* Facebook App ID (for Facebook login)
* Google Client ID (for Google login)

**Setup Instructions:**

1. Install and activate HivePress plugin
2. Install and activate ExtraLogin for HP
3. Create a Facebook App and get your App ID (if using Facebook login)
4. Create a Google OAuth Client and get your Client ID (if using Google login)
5. Go to HivePress > Settings > Users > Registration
6. Select your desired authentication methods
7. Go to HivePress > Settings > Integrations
8. Enter your Facebook App ID and/or Google Client ID
9. Save settings and test!

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/extralogin-for-hp/` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Make sure HivePress plugin is installed and activated
4. Configure the plugin via HivePress > Settings > Users and Integrations sections

== Frequently Asked Questions ==

= Does this plugin work without HivePress? =

No, this plugin requires HivePress to be installed and activated.

= Do I need API credentials? =

Yes, you need to create apps on Facebook and/or Google to get API credentials (App ID for Facebook, Client ID for Google).

= Is it free? =

Yes, ExtraLogin for HP is completely free to use.

= Which authentication methods are supported? =

Currently Facebook and Google authentication are supported.

== Changelog ==

= 1.0.0 =
* Initial release
* Facebook authentication support
* Google authentication support (updated to new Google Identity Services API)
* Seamless HivePress integration
