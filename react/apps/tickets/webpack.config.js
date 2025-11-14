const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const Dotenv = require('dotenv-webpack');

module.exports = {
    ...defaultConfig,
    plugins: [
        ...(defaultConfig.plugins || []),
        new Dotenv()
    ]
};