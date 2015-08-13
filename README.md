WordPress Plugin Template
=========================

A robust code template for creating a standards-compliant WordPress plugin.


## Why this template?

After writing many WordPress plugins I slowly developed my own coding style and way of doing things - this template is the culmination of what I've learnt along the way. I use this template as a base for any plugin that I start building and I thought it might benefit more people if I shared it around.

## How do I use it?

You can simply copy the files out of this repo and rename everything as you need it, but to make things easier I have included a [shell script](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/build-plugin.sh) in this repo that will automatically copy the files to a new folder, remove all traces of the existing git repo, rename everything in the files according to your new plugin name, and initialise a new git repo in the folder if you choose to do so.

### Running the script

You can run the script just like you would run any shell script - it does not take any arguments, so you don't need to worry about that. Once you start the script it will ask for three things:

1. **Plugin name** - this must be the full name of your plugin, with correct capitalisation and spacing.
2. **Destination folder** - this will be the folder where your new plugin will be created - typically this will be your `wp-content/plugins` folder. You can provide a path that is relative to the script, or an absolute path - either will work.
3. **Include Grunt support (y/n)** - if you enter 'y' here then the Grunt files will be included in the new plugin folder.
4. **Initialise new git repo (y/n)** - if you enter 'y' here then a git repo will be initialised in the new plugin folder.

### API functions

As of v3.0 of this template, there are a few libraries built into it that will make a number of common tasks a lot easier. I will expand on these libraries in future versions.

#### Registering a new post type

Using the [post type API](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/includes/lib/class-wordpress-plugin-template-post-type.php) and the wrapper function from the main plugin class you can easily register new post types with one line of code. For exapmle if you wanted to register a `listing` post type then you could do it like this:

`WordPress_Plugin_Template()->register_post_type( 'listing', __( 'Listings', 'wordpress-plugin-template' ), __( 'Listing', 'wordpress-plugin-template' ) );`

*Note that the `WordPress_Plugin_Template()` function name and the `wordpress-plugin-template` text domain will each be unique to your plugin after you have used the cloning script.*

This will register a new post type with all the standard settings. If you would like to modify the post type settings you can use the `{$post_type}_register_args` filter. See [the WordPress codex page](http://codex.wordpress.org/Function_Reference/register_post_type) for all available arguments.

#### Registering a new taxonomy

Using the [taxonomy API](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/includes/lib/class-wordpress-plugin-template-taxonomy.php) and the wrapper function from the main plugin class you can easily register new taxonomies with one line of code. For example if you wanted to register a `location` taxonomy that applies to the `listing` post type then you could do it like this:

`WordPress_Plugin_Template()->register_taxonomy( 'location', __( 'Locations', 'wordpress-plugin-template' ), __( 'Location', 'wordpress-plugin-template' ), 'listing' );`

*Note that the `WordPress_Plugin_Template()` function name and the `wordpress-plugin-template` text domain will each be unique to your plugin after you have used the cloning script.*

This will register a new taxonomy with all the standard settings. If you would like to modify the taxonomy settings you can use the `{$taxonomy}_register_args` filter. See [the WordPress codex page](http://codex.wordpress.org/Function_Reference/register_taxonomy) for all available arguments.

## What does this template give me?

This template includes the following features:

+ Plugin headers as required by WordPress & WordPress.org
+ Readme.txt file as required by WordPress.org
+ Main plugin class
+ Full & minified Javascript files
+ Grunt.js support
+ Standard enqueue functions for the dashboard and the frontend
+ A library for easily registering a new post type
+ A library for easily registering a new taxonomy
+ A library for handling common admin functions (including adding meta boxes to any post type, displaying settings fields and display custom fields for posts)
+ A complete and versatile settings class like you see [here](http://www.hughlashbrooke.com/complete-versatile-options-page-class-wordpress-plugin/)
+ A .pot file to make localisation easier
+ Full text of the GPLv2 license

See the [changelog](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/changelog.txt) for a complete list of changes as the template develops.

## I've got an idea/fix for the template

If you would like to contribute to this template then please fork it and send a pull request. I'll merge the request if it fits into the goals for the template and credit you in the [changelog](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/changelog.txt).

## This template is amazing! How can I ever repay you?

There's no need to credit me in your code for this template, but if you would like to buy me some lunch then you can [donate here](http://www.hughlashbrooke.com/donate).
