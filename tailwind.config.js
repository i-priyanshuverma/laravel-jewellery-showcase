import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Helvetica', 'Roboto', 'Arial', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                gold: {
                    50: '#fffdf5',
                    100: '#fff9e6',
                    200: '#fef0bf',
                    300: '#fde38a',
                    400: '#fcd34d',
                    500: '#d97706',
                    600: '#b45309',
                    700: '#92400e',
                    800: '#78350f',
                    900: '#451a03',
                    950: '#260e02',
                },
            },
            backgroundImage: {
                'gold-gradient': 'linear-gradient(135deg, #fcd34d 0%, #f59e0b 50%, #d97706 100%)',
                'gold-shimmer': 'linear-gradient(90deg, #fef3c7 0%, #fde68a 25%, #f59e0b 50%, #d97706 75%, #fef3c7 100%)',
            },
        },
    },

    plugins: [forms],
};
