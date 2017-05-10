var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.copy([
        'node_modules/bootstrap-sass/assets/fonts/*',
        'node_modules/font-awesome/fonts/**'
    ], 'public/fonts');
    mix.sass('../vendor/erpnetSaas/sass/app.scss')
     .browserify('../vendor/erpnetSaas/js/app.js')
     .version(['css/app.css', 'js/app.js']);
});
