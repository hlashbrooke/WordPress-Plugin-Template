/* jshint node:true */
module.exports = function( grunt ){
	'use strict';

	grunt.initConfig({
		// setting folder templates
		dirs: {
			css: 'assets/css',
			less: 'assets/css',
			js: 'assets/js'
		},

		// Compile all .less files.
		less: {
			compile: {
				options: {
					// These paths are searched for @imports
					paths: ['<%= less.css %>/']
				},
				files: [{
					expand: true,
					cwd: '<%= dirs.css %>/',
					src: [
						'*.less',
						'!mixins.less'
					],
					dest: '<%= dirs.css %>/',
					ext: '.css'
				}]
			}
		},

		// Minify all .css files.
		cssmin: {
			minify: {
				expand: true,
				cwd: '<%= dirs.css %>/',
				src: ['*.css'],
				dest: '<%= dirs.css %>/',
				ext: '.css'
			}
		},

		// Minify .js files.
		uglify: {
			options: {
				preserveComments: 'some'
			},
			jsfiles: {
				files: [{
					expand: true,
					cwd: '<%= dirs.js %>/',
					src: [
						'*.js',
						'!*.min.js',
						'!Gruntfile.js',
					],
					dest: '<%= dirs.js %>/',
					ext: '.min.js'
				}]
			}
		},

		// Watch changes for assets
		watch: {
			less: {
				files: [
					'<%= dirs.less %>/*.less',
				],
				tasks: ['less', 'cssmin'],
			},
			js: {
				files: [
					'<%= dirs.js %>/*js',
					'!<%= dirs.js %>/*.min.js'
				],
				tasks: ['uglify']
			}
		},

		// Build a zip file for deployment
		compress: {
			main: {
				options: {
					archive: 'wordpress-plugin-template.zip'
				},
				files: [
					{src: ['assets/css/*.css'], dest: 'assets/css/', filter: 'isFile'}, // includes css files in css path
					{src: ['assets/js/*.min.js'], dest: 'assets/js/', filter: 'isFile'}, // includes js files in js path
					{src: ['assets/**', '!assets/js', '!assets/css'], dest: 'assets/', filter: 'isFile'}, // includes any other assets outside js/css
					{src: ['includes/**'], dest: 'includes/', filter: 'isFile'}, //includes files in includes path
					{src: ['lang/**'], dest: 'lang/', filter: 'isFile' }, //includes files in lang path
					{src: ['vendor/**'], dest: 'vendor/', filter: 'isFile' }, //includes files in lang path
					{src: ['*.php', 'LICENSE', '*.txt'], filter: 'isFile' } //includes base directory files
				]
			}
		}

	});

	grunt.loadTasks('tasks');

	// Load NPM tasks to be used here
	grunt.loadNpmTasks( 'grunt-contrib-less' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-compress' );

	// Register tasks
	grunt.registerTask( 'default', [
		'less',
		'cssmin',
		'uglify'
	]);

	grunt.registerTask( 'build', [
		'less',
		'cssmin',
		'uglify',
		'compress'
	])

};