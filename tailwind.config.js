/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  content: ["./site/**/*.php"],
  theme: {
    extend: {},
  },
  plugins: [
    require("tailwindcss-fluid-type"),
    require("@tailwindcss/container-queries"),
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
  ],
};
