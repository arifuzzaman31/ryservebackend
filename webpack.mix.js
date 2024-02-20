const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    // .js('resources/js/attribute.js', 'public/js')
    // .js('resources/js/product.js', 'public/js')
    // .js('resources/js/order.js', 'public/js')
    // .js('resources/js/campaign.js', 'public/js')
    // .js('resources/js/pages.js', 'public/js')
    // .js('resources/js/refund.js', 'public/js')
    // .js('resources/js/category.js', 'public/js')
    // .js('resources/js/report.js', 'public/js')
    .js('resources/js/business.js', 'public/js')
    .vue()
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
