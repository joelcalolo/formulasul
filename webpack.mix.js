const mix = require('laravel-mix');

mix.js('resources/js/javascript.js', 'public/js')
   .postCss('resources/css/style.css', 'public/css', [
       require('tailwindcss'),
   ]);