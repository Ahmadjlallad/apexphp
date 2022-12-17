module.exports = {
  content: [
    "./resources/**/*.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/forms')],
}