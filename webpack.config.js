var webpack = require('webpack');
var configurator = require('@dosomething/webpack-config');

var config = configurator({
  entry: {
    'app': './resources/assets/app.js'
  },
});

// Override output path.
config.output.path = './public/dist';

module.exports = config;
