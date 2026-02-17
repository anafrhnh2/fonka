import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // 1. Font Family Merged Here
            fontFamily: {
            lexend: ['Lexend', 'sans-serif'],
            },
            
            // 2. Custom Colors Added Here
            colors: {
                'fun-blue': '#E0F2FE',
                'fun-yellow': '#FEF08A',
                'fun-pink': '#FCE7F3',
                'brand-blue': '#0EA5E9',
                'brand-dark': '#0C4A6E',
            },

            // 3. Custom Shadows Added Here
            boxShadow: {
                'pop': '0px 6px 0px 0px rgba(0,0,0,0.1)',
                'pop-hover': '0px 3px 0px 0px rgba(0,0,0,0.1)',
            }
        },
    },

    plugins: [forms],
};