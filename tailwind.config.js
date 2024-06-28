/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [ 
  './templates/**/*.html.twig',
  './assets/**/*.js',],
  theme: {
    extend: {
      colors: {
        'primary': '#1DACB9',
        'secondary': '#F28EAA',
        'nav-bg': '#FBFBFB',
        'bg': '#EDEFF2',
        'primary-Text': '#111928',
        'secondary-Text': '#8899A8',
        'strock': '#DFE4EA',
        'vert': '#E4F7ED'
      },
      boxShadow: {
        'custom': '0px 4px 4px rgba(0, 0, 0, 0.1)',
      },
      fontFamily: {
        'poppins': ['Poppins', 'sans-serif'],
      },
      padding: {
        '15': '90px',
        '30': '120px',
      },
      fontSize: {
        'h1': ['60px', '72px'],
        'h2': ['48px', '58px'],
        'h3': ['40px', '48px'],
        'h4': ['30px', '38px'],
        'h5': ['28px', '40px'],
        'h6': ['24px', '30px'],
        'body-large': ['18px', '26px'],
        'body-medium': ['16px', '24px'],
        'body-extra-large': ['40px', '48px'],
        'lien': ['18px', '26px'],
      },
      fontWeight: {
        'regular': 400,
        'medium': 500,
        'semibold': 600,
        'bold': 700,
      },
    },
  },
  plugins: [],
}

