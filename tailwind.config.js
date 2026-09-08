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
            fontFamily: {
                lexend: ['Lexend', 'sans-serif'],
            },

            colors: {
                'brand-blue': '#E0F2FE',
                'brand-yellow': '#FEF08A',
                'brand-pink': '#FCE7F3',
                'brand-green': '#84CC16',
                'brand-dark': '#0C4A6E',
                'brand-bg': '#F0FDF4',
                'brand-primary': '#22C55E',
                'brand-secondary': '#60A5FA',
                'brand-accent': '#F472B6',
                'brand-text': '#0F172A',
            },

            boxShadow: {
                'pop': '0px 6px 0px 0px rgba(0,0,0,0.1)',
                'pop-hover': '0px 3px 0px 0px rgba(0,0,0,0.1)',
            }
        },
    },

    plugins: [forms],
};