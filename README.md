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
3. **Initialise new git repo (y/n)** - if you enter 'y' here then a git repo will be initialised in the new plugin folder.

## What does this template give me?

This template includes the following features:

+ Plugin headers as required by WordPress & WordPress.org
+ Readme.txt file as required by WordPress.org
+ Main plugin class
+ Full & minified Javascript files
+ Grunt.js support
+ Standard enqueue functions for the dashboard and the frontend
+ A class (not utilised in the template directly) that can be used to create a new custom post type and custom taxonomies
+ A complete and versatile settings class like you see [here](http://www.hughlashbrooke.com/complete-versatile-options-page-class-wordpress-plugin/)
+ A .pot file to make localisation easier
+ Full text of the GPLv3 license

See the [changelog](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/changelog.txt) for a complete list of changes as the template develops.

## I've got an idea/fix for the template

If you would like to contribute to this template then please fork it and send a pull request. I'll merge the request if it fits into the goals for the template and credit you in the [changelog](https://github.com/hlashbrooke/WordPress-Plugin-Template/blob/master/changelog.txt).

## This template is amazing! How can I ever repay you?

There's no need to credit me in your code for this template, but if you would like to buy me some lunch then you can [donate here](http://www.hughlashbrooke.com/donate).
