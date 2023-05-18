/** @type {import('tailwindcss').Config} */
const plugin = require("tailwindcss/plugin");

module.exports = {
  content: ["./site/**/*.php"],

  theme: {
    extend: {
      screens: {
        screen: { raw: "screen" },
      },
    },
  },

  plugins: [
    require("@tailwindcss/container-queries"),
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),

    // Cusotm plugins
    plugin(function ({ addVariant, e }) {
      // Add “js:” variant
      addVariant("js", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.js .${e(`js${separator}${className}`)}`;
        });
      });

      // Add “no-js:” variant
      addVariant("no-js", ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => {
          return `.no-js .${e(`no-js${separator}${className}`)}`;
        });
      });
    }),
  ],
};
