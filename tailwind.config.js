/** @type {import('tailwindcss').Config} */
const plugin = require("tailwindcss/plugin");
const hexToRgb = (hex) => {
  hex = hex.replace("#", "");
  hex = hex.length === 3 ? hex.replace(/./g, "$&$&") : hex;
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);
  return `${r} ${g} ${b}`;
};

module.exports = {
  content: ["./site/**/*.php", "./src/**/*.js"],

  theme: {
    extend: {
      fontFamily: {
        primary: ["Nunito Sans", "sans-serif"],
      },
      screens: {
        screen: {
          raw: "screen",
        },
      },
      spacing: {
        small: "0.75rem",
        medium: "1.5rem",
        large: "3rem",
        xlarge: "6rem",
      },
      typography: ({ theme }) => ({
        black: {
          css: {
            "--tw-prose-body": "#000000",
            "--tw-prose-headings": "#000000",
            "--tw-prose-lead": "#000000",
            "--tw-prose-links": "#000000",
            "--tw-prose-bold": "#000000",
            "--tw-prose-counters": "#000000",
            "--tw-prose-bullets": "#000000",
            "--tw-prose-hr": "#000000",
            "--tw-prose-quotes": "#000000",
            "--tw-prose-quote-borders": "#000000",
            "--tw-prose-captions": "#000000",
            "--tw-prose-code": "#000000",
            "--tw-prose-pre-code": "#ffffff",
            "--tw-prose-pre-bg": "#000000",
            "--tw-prose-th-borders": "#000000",
            "--tw-prose-td-borders": "#000000",
            "--tw-prose-invert-body": "#ffffff",
            "--tw-prose-invert-headings": "#ffffff",
            "--tw-prose-invert-lead": "#ffffff",
            "--tw-prose-invert-links": "#ffffff",
            "--tw-prose-invert-bold": "#ffffff",
            "--tw-prose-invert-counters": "#ffffff",
            "--tw-prose-invert-bullets": "#ffffff",
            "--tw-prose-invert-hr": "#ffffff",
            "--tw-prose-invert-quotes": "#ffffff",
            "--tw-prose-invert-quote-borders": "#ffffff",
            "--tw-prose-invert-captions": "#ffffff",
            "--tw-prose-invert-code": "#ffffff",
            "--tw-prose-invert-pre-code": "#ffffff",
            "--tw-prose-invert-pre-bg": "#000000",
            "--tw-prose-invert-th-borders": "#ffffff",
            "--tw-prose-invert-td-borders": "#ffffff",
          },
        },
        mvlkss: {
          css: {
            "--tw-prose-body": theme("colors.neutral[700]"),
            "--tw-prose-headings": theme("colors.neutral[900]"),
            "--tw-prose-lead": theme("colors.neutral[600]"),
            "--tw-prose-links": theme("colors.blue[700]"),
            "--tw-prose-bold": theme("colors.neutral[900]"),
            "--tw-prose-counters": theme("colors.neutral[500]"),
            "--tw-prose-bullets": theme("colors.neutral[300]"),
            "--tw-prose-hr": theme("colors.neutral[200]"),
            "--tw-prose-quotes": theme("colors.neutral[900]"),
            "--tw-prose-quote-borders": theme("colors.neutral[200]"),
            "--tw-prose-captions": theme("colors.neutral[500]"),
            "--tw-prose-kbd": theme("colors.neutral[900]"),
            "--tw-prose-kbd-shadows": hexToRgb(theme("colors.neutral[900]")),
            "--tw-prose-code": theme("colors.neutral[900]"),
            "--tw-prose-pre-code": theme("colors.neutral[200]"),
            "--tw-prose-pre-bg": theme("colors.neutral[800]"),
            "--tw-prose-th-borders": theme("colors.neutral[300]"),
            "--tw-prose-td-borders": theme("colors.neutral[200]"),
            "--tw-prose-invert-body": theme("colors.neutral[300]"),
            "--tw-prose-invert-headings": theme("colors.white"),
            "--tw-prose-invert-lead": theme("colors.neutral[400]"),
            "--tw-prose-invert-links": theme("colors.blue[300]"),
            "--tw-prose-invert-bold": theme("colors.white"),
            "--tw-prose-invert-counters": theme("colors.neutral[400]"),
            "--tw-prose-invert-bullets": theme("colors.neutral[600]"),
            "--tw-prose-invert-hr": theme("colors.neutral[700]"),
            "--tw-prose-invert-quotes": theme("colors.neutral[100]"),
            "--tw-prose-invert-quote-borders": theme("colors.neutral[700]"),
            "--tw-prose-invert-captions": theme("colors.neutral[400]"),
            "--tw-prose-invert-kbd": theme("colors.white"),
            "--tw-prose-invert-kbd-shadows": hexToRgb(theme("colors.white")),
            "--tw-prose-invert-code": theme("colors.white"),
            "--tw-prose-invert-pre-code": theme("colors.neutral[300]"),
            "--tw-prose-invert-pre-bg": "rgb(0 0 0 / 50%)",
            "--tw-prose-invert-th-borders": theme("colors.neutral[600]"),
            "--tw-prose-invert-td-borders": theme("colors.neutral[700]"),
          },
        },
        white: {
          css: {
            "--tw-prose-body": "#ffffff",
            "--tw-prose-headings": "#ffffff",
            "--tw-prose-lead": "#ffffff",
            "--tw-prose-links": "#ffffff",
            "--tw-prose-bold": "#ffffff",
            "--tw-prose-counters": "#ffffff",
            "--tw-prose-bullets": "#ffffff",
            "--tw-prose-hr": "#ffffff",
            "--tw-prose-quotes": "#ffffff",
            "--tw-prose-quote-borders": "#ffffff",
            "--tw-prose-captions": "#ffffff",
            "--tw-prose-code": "#ffffff",
            "--tw-prose-pre-code": "#ffffff",
            "--tw-prose-pre-bg": "#000000",
            "--tw-prose-th-borders": "#ffffff",
            "--tw-prose-td-borders": "#ffffff",
            "--tw-prose-invert-body": "#000000",
            "--tw-prose-invert-headings": "#000000",
            "--tw-prose-invert-lead": "#000000",
            "--tw-prose-invert-links": "#000000",
            "--tw-prose-invert-bold": "#000000",
            "--tw-prose-invert-counters": "#000000",
            "--tw-prose-invert-bullets": "#000000",
            "--tw-prose-invert-hr": "#000000",
            "--tw-prose-invert-quotes": "#000000",
            "--tw-prose-invert-quote-borders": "#000000",
            "--tw-prose-invert-captions": "#000000",
            "--tw-prose-invert-code": "#000000",
            "--tw-prose-invert-pre-code": "#ffffff",
            "--tw-prose-invert-pre-bg": "#000000",
            "--tw-prose-invert-th-borders": "#000000",
            "--tw-prose-invert-td-borders": "#000000",
          },
        },
      }),
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
