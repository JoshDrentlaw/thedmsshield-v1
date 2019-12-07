require("babel-polyfill");
require('dotenv').config()
const path = require('path');
const withPlugins = require("next-compose-plugins");
const withSourceMaps = require('@zeit/next-source-maps')
const withCSS = require('@zeit/next-css')
//const withReactSvg = require('next-react-svg')

module.exports = withPlugins(
    [
        withCSS,
        withSourceMaps
    ],
    {
        env: {
            // Reference a variable that was defined in the .env file and make it available at Build Time
            ENDPOINT: process.env.ENDPOINT
        },
        webpack(config, options) {
            return config
        }
    }
)

/* module.exports = withSourceMaps({
    webpack(config, options) {
        return config
    }
})

require('dotenv').config()
module.exports = {
    env: {
        // Reference a variable that was defined in the .env file and make it available at Build Time
        ENDPOINT: process.env.ENDPOINT
    },
}

module.exports = withCSS({
    target: 'serverless',
    webpack: function (config) {
        config.module.rules.push(
            {
                test: /\.css$\i/,
                use: ['style-loader', 'css-loader']
            },
            {
                test: /\.(eot|woff|woff2|ttf|svg|png|jpg|gif)$/,
                use: {
                    loader: 'url-loader',
                    options: {
                        limit: 100000,
                        name: '[name].[ext]'
                    }
                }
            }
        )
        return config
    }
})

module.exports = withReactSvg({
    include: path.resolve(__dirname, 'public/svg'),
    webpack(config, options) {
        return config
    }
}) */