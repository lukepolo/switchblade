# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
environment = :development
http_path = "/"
#Compile Paths
css_dir = "./public/assets/css"
images_dir = "./resources/assets/images"

sass_dir = "./resources/assets/sass"
javascripts_dir = "./resources/assets/js"

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed
output_style = :compressed

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false

additional_import_paths = [
    "./vendor/bower_components/bootstrap-sass-official/assets/stylesheets",
    "./vendor/bower_components/fontawesome/scss"
]