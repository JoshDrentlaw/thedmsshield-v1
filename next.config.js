const path = require('path');

const withSourceMaps = require('@zeit/next-source-maps')
module.exports = withSourceMaps({
    webpack(config, options) {
        return config
    }
})

require('dotenv').config()
module.exports = {
    env: {
        // Reference a variable that was defined in the .env file and make it available at Build Time
        host: process.env.ENDPOINT,
        database: process.env.DATABASE,
        user: process.env.USERNAME,
        password: process.env.PASSWORD
    },
}

const withCSS = require('@zeit/next-css')
module.exports = withCSS({
    cssModules: true
})

const withReactSvg = require('next-react-svg')
module.exports = withReactSvg({
    include: path.resolve(__dirname, 'public/svg'),
    webpack(config, options) {
        return config
    }
})