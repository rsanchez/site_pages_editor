# Site Pages Editor

![Screenshot](http://rsanchez.github.io/site_pages_editor/images/screenshot.png)

ExpressionEngine stores Page URIs and template selections for the Pages/Structure modules in a base64 encoded and serialized array.

Site Pages Editor allows you to view and edit the contents of this serialized array.

This add-on is intended for advanced users who understand the inner workings of the Pages module and the Site Pages array. Please backup your database before you first use this add-on.

## Installation

* Copy the /system/expressionengine/third_party/site_pages_editor/ folder to your /system/expressionengine/third_party/ folder
* Install the module

## Configuration

You may set `$config['site_pages_editor_readonly'] = true` in your `config.php` file to use Site Pages Editor in read-only mode.