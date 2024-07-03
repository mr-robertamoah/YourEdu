import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
  // purge: [],
    content: [
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
      './storage/framework/views/*.php',
      './resources/views/**/*.blade.php',
      './resources/js/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        'whitesmoke': '#f5f5f5',
        'wheat': '#f5deb3',
        'mainbrown': '#a62a2b'
      },
      minWidth: {
        '0': '0',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
        '10': '20px',
        '20': '40px',
        'screen': '100vw',
        'content': 'fit-content',
      },
      maxWidth: {
        '0': '0',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
        'screen': '100vh',
        'content': 'fit-content',
      },
      minHeight: {
        '0': '0',
        '1/4': '25%',
        '1/3': '33.333333333%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
        '90vh': '90vh',
        'screen': '100vh',
        'content': 'fit-content',
      },
      maxHeight: {
        '0': '0',
        '1/4': '25%',
        '1/3': '33.333333333%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
        'screen': '100vh',
        'content': 'fit-content',
      },
      height: {
        "90vh": "90vh",
        '10/12': '83.333333333333%',
        'content': 'fit-content',
      },
      width: {
        "content": "fit-content"
      },
      margin: {
        "1/4": "25%",
        '1/2': '50%',
        '3/4': '75%',
        "-1/4": "-25%",
        '-1/2': '-50%',
        '-3/4': '-75%',
        '1/6': '16.666667%',
        '1/12': '8.333333%',
      },
      cursor: {
        "nwse": "nwse-resize",
        "nesw": "nesw-resize",
      },
    },
  },
  variants: {
    extend: {
      borderWidth: ['hover', 'responsive', 'active', 'focus'],
      width: ['hover',],
      height: ['hover',],
      backgroundColor: ['disabled','hover'],
      textColor: ['disabled','hover'],
      cursor: ['disabled'],
      transitionProperty: ['responsive', 'hover', 'focus'],
    },
  },
  plugins: [],
}
