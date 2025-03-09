/** @type {import('postcss-load-config').Config} */
module.exports = ({ options, env }) => ({
  plugins: [
    require("@tailwindcss/postcss")(),
    env === "production" ? require("cssnano")(options.cssnano) : false,
  ].filter(Boolean),
});
