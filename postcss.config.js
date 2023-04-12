module.exports = ({ options, env }) => ({
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
    cssnano: env === "production" ? options.cssnano : false,
  },
});
