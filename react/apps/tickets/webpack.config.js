const path = require('path');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const Dotenv = require('dotenv-webpack');

module.exports = {
    ...defaultConfig,
    plugins: [
        ...(defaultConfig.plugins || []),
        new Dotenv({
            path: path.resolve(__dirname, '../../../../.env'), // points to wp-content/.env
        }),
    ],
};