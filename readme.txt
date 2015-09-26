=== Plugin Name ===
Contributors: Ipstenu
Tags: reply, at-reply, comments
Requires at least: 4.0
Tested up to: 4.3
Stable tag: 2.0

This plugin allows you to add @reply links to comments.

== Description ==

This plugin allows you to add Twitter-like @reply links to comments. When clicked, those links insert the author name and a link to the comment you are replying to in the text.

In addition, the Dashboard allows you to click to expand the parent comment (with a handy word count for those prolific commenters).

= Credit =

Forked from the following:

* http://wordpress.org/extend/plugins/custom-smilies/
* http://wordpress.org/plugins/reply-to

== Changelog ==

= 2.0.1 =
* Corrected i18n (dreadfully embarrassing, copied the wrong file up)
* Added screenshots

= 2.0 = 
* Released on WordPress.org
* Added DocBlox
* Added 'Show Parent Comment' to dashboard pages

= 1.0.2 = 
* Adding warning message when Threaded Comments aren't enabled
* Cleaned code

= 1.0 - Initial Release =
* Removed images
* Removed non-threaded support
* Moved code to it's own class

== Installation ==

No customization needed.

== Frequently Asked Questions ==

= I activated the plugin but I can't see the reply links. =

If you're not using threaded comments, there is no reply link. And you should get a warning.

= I can see the reply links but clicking them does nothing. =

Either you have disabled JavaScript in your browser or your WordPress theme is not using the default id for the comments textarea (which is `comment`).

= It shows the full parent comment on the dashboard =

You must be on IE8. There's a details shim for Firefox and IE9+, but I drew the line at supporting IE8. Sorry banks.

== Screenshots ==

1. Front End (TwentyFifteen)
2. Post Edit Page
3. Main Comments Page (with some parents expanded)