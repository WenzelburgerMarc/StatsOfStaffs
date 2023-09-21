/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            screens: {
                'xs': '410px',
            },
            colors: {
                primary: {
                    '100': '#EBF4FF',
                    '200': '#C3DAFE',
                    '300': '#A3BFFA',
                    '400': '#7F9CF5',
                    '500': '#667EEA',
                    '600': '#5A67D8',
                    '700': '#4C51BF',
                    '800': '#434190',
                    '900': '#3C366B',
                },
            },
        },
    },
    plugins: [require("nightwind")],
}
