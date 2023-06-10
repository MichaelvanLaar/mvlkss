/** @type {import('tailwindcss').Config} */
const plugin = require("tailwindcss/plugin");

module.exports = {
  content: ["./site/**/*.php", "./src/**/*.js"],

  theme: {
    extend: {
      spacing: {
        small: "0.75rem",
        medium: "1.5rem",
        large: "3rem",
        xlarge: "6rem",
      },
      screens: {
        screen: {
          raw: "screen",
        },
      },
    },
  },

  plugins: [
    require("@tailwindcss/container-queries"),
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),

    // Custom plugins
    plugin(function ({ addVariant, e }) {
      // Add “js:” variant
      // (i.e. is applied when there is a “js” class on the html element)
      addVariant("js", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.js .${e(`js${separator}${className}`)}`;
        });
      });

      // Add “no-js:” variant
      // (i.e. is applied when there is a “no-js” class on the html element)
      addVariant("no-js", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.no-js .${e(`no-js${separator}${className}`)}`;
        });
      });
    }),
  ],
};
