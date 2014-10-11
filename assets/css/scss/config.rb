# Require any additional compass plugins here.
require 'compass/import-once/activate'
environment = :development

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "../"
sass_dir = "./"

if environment == :production
    output_style = :compressed
    sourcemap = false
    line_comments = false
else
    environment == :development
    output_style = :expanded
    sourcemap = true
    line_comments = true
end

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true