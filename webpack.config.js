const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
  entry: ["./src/js/main.js", "./src/js/maincss.js"],
  output: {
    path: path.resolve(__dirname, "assets/js"),
    filename: "main.js",
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: "../css/[name].css",
    }),
  ],
  module: {
    rules: [
      {
        test: /\.(?:js|mjs|cjs)$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: [["@babel/preset-env", { modules: false }]],
          },
        },
      },
      {
        test: /\.css$/i,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: "css-loader",
            options: { importLoaders: 1, sourceMap: true },
          },
          "postcss-loader",
        ],
      },
    ],
  },
};
