const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CopyPlugin = require("copy-webpack-plugin");

module.exports = {
  entry: [
    "./src/js/main.js",
    "./src/js/maincss.js",
    "./src/js/highlightjscss.js",
  ],
  output: {
    path: path.resolve(__dirname, "assets/js"),
    filename: "main.js",
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].css",
    }),
    new CopyPlugin({
      patterns: [
        // Copy all files in the `/src/images` directory to the `/assets/images`
        // directory – even if they are not referenced in a JS or CSS file
        { from: "src/images", to: "../images" },

        // Copy all files in the `/src/fonts` directory to the `/assets/fonts`
        // directory – even if they are not referenced in a JS or CSS file
        { from: "src/fonts", to: "../fonts" },
      ],
    }),
  ],
  module: {
    rules: [
      {
        // Process JS files
        test: /\.(?:js|mjs|cjs)$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [
              [
                "@babel/preset-env",
                {
                  modules: false,
                },
              ],
            ],
          },
        },
      },
      {
        // Process CSS files
        test: /\.css$/i,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: "css-loader",
            options: {
              importLoaders: 1,
              sourceMap: true,
            },
          },
          "postcss-loader",
        ],
      },
      {
        // Copy all font files in use to the `/assets/fonts` directory.
        // The original font files must be stored in the `/src/fonts` directory.
        // This is required because SVG files may be used for webfonts as well
        // as for images. That’s why only SVG files in the `/src/fonts`
        // directory should be processed by this rule. SVG files in the
        // `/src/images` directory are processed by the “copy-webpack-plugin”.
        test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        include: path.resolve(__dirname, "src/fonts"),
        type: "asset/resource",
        generator: {
          filename: "../fonts/[name][ext][query]",
        },
      },
      {
        // Copy all image files, which are in use and which are stored in
        //`/src/images` to the `/assets/images` directory. This prevents
        // duplicates because the default behavior of webpack is storing a
        // renamed version of the image in the `/assets/js` directory. But since
        // images from `/src/images` are already copied to `/assets/images` by
        // the “copy-webpack-plugin”, we would have the same image twice within
        // the `/assets` directory.
        test: /\.(jp(e)?g|png|gif|webp|svg)(\?v=\d+\.\d+\.\d+)?$/,
        include: path.resolve(__dirname, "src/images"),
        type: "asset/resource",
        generator: {
          filename: "../images/[name][ext][query]",
        },
      },
    ],
  },
};
