const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.js('src/resources/js/app.js', 'src/public/js')
   .postCss('src/resources/css/app.css', 'src/public/css', [
      tailwindcss('./tailwind.config.js'),
   ]);
