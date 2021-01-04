=== Remove noreferrer ===
Contributors: gruz0
Donate link: https://www.buymeacoffee.com/gruz0
Tags: post, page, widgets, comments, noreferrer, affiliate, marketing
Requires at least: 5.1
Requires PHP: 5.6
Tested up to: 5.6
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

**"Remove noreferrer" automatically removes <code>rel="noreferrer"</code> attribute from links on your website on-the-fly.**

Plugin does not modify original links or content in the database.

### Which kind of content supported?

* Posts
* Pages
* Blog page (homepage, etc.)
* Comments

Also it supports standard WordPress widgets:

* "Text"
* "Custom HTML"

= Docs & Support =

This plugin is an open source project and we would love you to help us make it better. If you want a new feature will be implemented in this plugin, you can open a [GitHub Issue](https://github.com/gruz0/remove-noreferrer/issues/new). If you don't have a GitHub Account you can send me email to [alexander@kadyrov.dev](mailto:alexander@kadyrov.dev). You can find more detailed information about plugin on [GitHub](https://github.com/gruz0/remove-noreferrer).

== Installation ==

### Install Remove Noreferrer within WordPress

1. Visit the plugins page within your dashboard and select "Add New"
2. Search for "remove noreferrer"
3. Activate plugin from your Plugins page

### Install Remove Noreferrer manually

1. Upload the "remove-noreferrer" folder to the <code>/wp-content/plugins/</code> directory
2. Activate the plugin through the "Plugins" menu in WordPress

== Screenshots ==
1. Before installation
2. After installation
3. Plugin's settings

== Changelog ==

= 2.0.0 =

* [Add support for PHP 5.6 and WordPress 5.1](https://github.com/gruz0/remove-noreferrer/pull/74)
* [Remove noreferrer from "Text" widgets](https://github.com/gruz0/remove-noreferrer/pull/29)
* [Remove noreferrer from "Custom HTML" widgets](https://github.com/gruz0/remove-noreferrer/pull/37)
* [Do not modify content if links or `noreferrer` attributes are not found](https://github.com/gruz0/remove-noreferrer/pull/37)
* [Add notice after settings saved](https://github.com/gruz0/remove-noreferrer/pull/44)
* [Remove plugin settings on uninstall](https://github.com/gruz0/remove-noreferrer/pull/52)
* [Migrate plugin settings to new version](https://github.com/gruz0/remove-noreferrer/pull/83)

= 1.2.0 =

* [Remove noreferrer from comments](https://github.com/gruz0/remove-noreferrer/pull/22)

= 1.1.1 =

* [Fix incorrect usage of loading default options](https://github.com/gruz0/remove-noreferrer/pull/15)
* [Extract plugin's option key to const](https://github.com/gruz0/remove-noreferrer/pull/17)

= 1.1.0 =

* Added plugin's options page
* Added possibility to remove `noreferrer` from Home and static Pages
* Removed extra spaces from `rel` attribute

== Donations ===

[Buy Me a Coffee](https://www.buymeacoffee.com/gruz0)
