import defaultTheme from 'tailwindcss/defaultTheme';
import preset from './vendor/filament/support/tailwind.config.preset';
import tallstackuiPrest from './vendor/tallstackui/tallstackui/tailwind.config.js';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    presets: [
        preset,
        tallstackuiPrest
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/tallstackui/tallstackui/src/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    DEFAULT: '#0B3D91',
                    '50': '#e6eef9',
                    '100': '#c8d8f0',
                    '200': '#9bb6e3',
                    '300': '#6a8dd3',
                    '400': '#406bc0',
                    '500': '#0B3D91',
                    '600': '#09357e',
                    '700': '#072b68',
                    '800': '#062254',
                    '900': '#051b44',
                    '950': '#020e26',
                },
                'dark': {
                    DEFAULT: '#3f4d69',
                    '50': '#f6f7f9',
                    '100': '#ebeef3',
                    '200': '#d3d9e4',
                    '300': '#acb8cd',
                    '400': '#7f92b1',
                    '500': '#5f7498',
                    '600': '#4b5d7e',
                    '700': '#3f4d69',
                    '800': '#364156',
                    '900': '#30384a',
                    '950': '#202531',
                }
            }
        },
    },
    plugins: [forms],
};
