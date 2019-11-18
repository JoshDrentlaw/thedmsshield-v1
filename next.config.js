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