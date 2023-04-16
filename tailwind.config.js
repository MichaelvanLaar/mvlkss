/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./site/**/*.php"],
  theme: {
    extend: {},
  },
  plugins: [
    require("tailwindcss-fluid-type"),
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
  ],
};
