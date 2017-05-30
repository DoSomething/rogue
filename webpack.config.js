const webpack = require('webpack');
const configure = require('@dosomething/webpack-config');
const path = require('path');

// Configure Webpack using `@dosomething/webpack-config`.
module.exports = configure({
  entry: {
    app: './resources/assets/app.js',
  },
  output: {
    // Override output path for Laravel's "public" directory.
    path: path.join(__dirname, '/public/dist'),
  },
});
