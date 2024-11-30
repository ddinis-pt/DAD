import animate from "tailwindcss-animate";
import preline from "preline/plugin";
import colors from "tailwindcss/colors";


/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ["class"],
    safelist: ["dark"],
    prefix: "",

    content: [
        './pages/**/*.{js,jsx,vue}',
        './components/**/*.{js,jsx,vue}',
        './app/**/*.{js,jsx,vue}',
        './src/**/*.{js,jsx,vue}',
        './node_modules/preline/preline.js',
    ],

    theme: {
        extend: {
            colors: {
                'blue': '#1fb6ff',
                'purple': '#7e5bef',
                'pink': '#ff49db',
                'orange': '#ff7849',
                'green': '#13ce66',
                'yellow': '#ffc82c',
                'gray-dark': '#273444',
                'gray': '#8492a6',
                'gray-light': '#d3dce6',
                'light-text': '#E5E7EB',
                'blue-text': '#334155',
                ...colors,
            }
        },
    },
    plugins: [
        animate,
        preline,
     colors,
    ],


};