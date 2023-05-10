/** @type {import('tailwindcss').Config} */

module.exports = {
  content: ["./site/**/*.php"],
  theme: {
    extend: {
      screens: {
        "screen": {"raw": "screen"},
      },
    },
  },
  plugins: [
    require("@tailwindcss/container-queries"),
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
  ],
};
