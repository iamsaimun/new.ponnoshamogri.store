/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: false, // or 'media' or 'class'
    content: [
      "./resources/views/front/**/*.blade.php",
      "./resources/views/vendor/pagination/tailwind.blade.php",
      './node_modules/tw-elements/dist/js/**/*.js'
    ],
    theme: {
      extend: {
        container: {
          center: true,
          padding: '1rem'
        },
        colors: {
          'primary': 'var(--primary)',
          'primary-light': 'var(--primary_light)',
          'secondary': 'var(--secondary)',
          'secondary-dark': 'var(--secondary_dark)'
        }
      },
    },
    plugins: [
      require('tw-elements/dist/plugin'),
      function ({ addComponents }) {
        addComponents({
          '.container': {
            maxWidth: '100%',
            '@screen sm': {
              maxWidth: '576px',
            },
            '@screen md': {
              maxWidth: '768px',
            },
            '@screen lg': {
              maxWidth: '922px',
            },
            '@screen xl': {
              maxWidth: '1140px',
            },
          }
        })
      }
    ],
  }
