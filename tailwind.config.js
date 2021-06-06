module.exports = {
  purge: [],
  theme: {
    extend: {
      minWidth: {
        '0': '0',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
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
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
        'screen': '100vh',
        'content': 'fit-content',
      },
      maxHeight: {
        '0': '0',
        '1/4': '25%',
        '1/2': '50%',
        '3/4': '75%',
        'full': '100%',
        'screen': '100vh',
        'content': 'fit-content',
      },
      height: {
        "90vh": "90vh"
      }
    },
  },
  variants: {
    borderWidth: ['hover', 'responsive', 'active', 'focus'],
    transitionProperty: ['responsive', 'hover', 'focus'],
  },
  plugins: [],
}
