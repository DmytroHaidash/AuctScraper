const mix = require('laravel-mix');

mix.sass('resources/sass/admin/app.scss', 'public/css/admin.css')
    .css('resources/css/app.css', 'public/css/app.css')
  .js('resources/js/app.js', 'public/js/app.js');