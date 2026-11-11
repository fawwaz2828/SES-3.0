/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./*.php', './admin/**/*.php', './includes/**/*.php', './src/**/*.php'],
    // `dark:` utilities apply while the landing-page light is still off.
    darkMode: ['selector', '.lights-off'],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
};
