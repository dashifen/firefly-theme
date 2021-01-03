const path = require('path');
const webpack = require('webpack');
const { VueLoaderPlugin } = require('vue-loader');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const filename = 'firefly.js';

module.exports = (env = {}) => ({
  mode: env.prod ? 'production' : 'development',
  devtool: env.prod ? 'source-map' : 'eval-cheap-module-source-map',
  entry: path.resolve(__dirname, './assets/scripts/' + filename),
  watch: !env.prod,
  output: {
    path: path.resolve(__dirname, './assets'),
    filename: filename,
    publicPath: '/assets/'
  },
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'assets/scripts'),
    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: 'vue-loader'
      },
      {
        test: /\.css$/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader
          },
          'css-loader'
        ]
      },
    ]
  },
  plugins: [
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
      filename: 'firefly.css'
    }),
    new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: 'true',
      __VUE_PROD_DEVTOOLS__: 'false'
    })
  ],
  watchOptions: {
    ignored: ['node_modules/**']
  }
})
