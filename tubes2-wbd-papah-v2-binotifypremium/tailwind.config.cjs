/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        'main-green': '#1ED760',
        'main-black-1' : '#535353',
        'main-black-2': '#191414',
        'main-black-3': '#212121',
        'main-red': '#E20000',
      },
      fontFamily: {
        main: ["Rubik"]
      },
      minWidth: {
        '100vw': '100vw'
      },
      width: {
        '100vw': '100vw',
        '1/8': '12.5%',
        '1/10': '10%'
      }
    },
  },
  plugins: [],
}
