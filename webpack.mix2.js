let mix = require('laravel-mix');
const del = require('del');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.options({
   processCssUrls: false,
});

mix.js('public/backend/default/javascripts/compile.js', 'public/backend/default/javascripts/compile.min.js')
   .combine([
      'public/backend/default/vendor/modernizr/modernizr.min.js',
      'public/backend/default/javascripts/compile.min.js',
      'public/backend/default/vendor/chosen/chosen.jquery.min.js',
      'public/backend/default/vendor/pnotify/pnotify.custom.js',
      'public/backend/default/javascripts/theme.custom.js',
   ], 'public/backend/default/javascripts/main.js');

mix.combine(['public/backend/default/javascripts/theme.js', 'public/backend/default/javascripts/theme.init.js'],'public/backend/default/javascripts/script.js');   

mix.combine([
   'public/backend/default/vendor/trumbowyg/trumbowyg.min.js', 
   'public/backend/default/vendor/trumbowyg/trumbowyg.resizimg.min.js',
   'public/backend/default/vendor/trumbowyg/trumbowyg.upload.min.js',
   'public/backend/default/vendor/flatpickr/flatpickr.min.js',
   'public/backend/default/vendor/mask/cleave.min.js',
   'public/backend/default/vendor/jquery-datatables/media/js/jquery.dataTables.min.js',
],'public/backend/default/javascripts/pjax.js');   

mix.sass('public/backend/default/stylesheets/theme.scss', 'public/backend/default/stylesheets/theme.css');

