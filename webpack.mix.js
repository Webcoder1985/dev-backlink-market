const mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
 mix.setPublicPath('public');
 mix.setResourceRoot('../');

mix.js('resources/js/app.js', 'public/js')
    .js('node_modules/@fortawesome/fontawesome-free/js/all.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .copyDirectory('resources/images', 'public/images')
    // .sass('resources/sass/normalize.scss', 'public/css')npm
    .sass('resources/sass/bootstrap.min.scss', 'public/css')
    // .sass('resources/sass/responsive.scss', 'public/css')
    .sass('resources/sass/customstyle.scss', 'public/css')
    .sass('resources/sass/autocomplete.scss', 'public/css')
    .sass('resources/sass/style.scss', 'public/css')
    .options({
        processCssUrls: true,
      // extractVueStyles: false,
       postCss: [require('autoprefixer')],
     })
     .purgeCss({
         whitelistPatterns: [/Cookie--nx-theme$/],
         whitelistPatternsChildren: [/Cookie--nx-theme$/]
     });