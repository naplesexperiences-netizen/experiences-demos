/**
 * Config Tailwind per la compilazione di assets/css/tailwind.min.css.
 * Stessa config che era inline nel Play CDN (header.php) fino alla v2.1.x.
 *
 * Per ricompilare dopo aver aggiunto classi nuove nei template:
 *   npx tailwindcss@3.4.17 -c tailwind.config.js \
 *       -i <(echo '@tailwind base;@tailwind components;@tailwind utilities;') \
 *       -o assets/css/tailwind.min.css --minify
 * (oppure salva le tre direttive @tailwind in un input.css)
 */
module.exports = {
  content: [
    './**/*.php',
    './assets/js/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary:   '#0B3D61',
        secondary: '#0D7C7C',
        accent:    '#14A3A3',
        dark:      '#0A1628',
        light:     '#E8F4F4',
      },
      fontFamily: {
        sans:    ['Inter', 'sans-serif'],
        heading: ['Montserrat', 'sans-serif'],
      },
    },
  },
};
