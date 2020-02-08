require('dotenv').config()
const express = require('express')
const next = require('next')
const bodyParser = require('body-parser')
const fileUpload = require('express-fileupload')
const Pusher = require('pusher')

const PORT = process.env.PORT || 3000
const dev = process.env.DEV !== 'production' //true false

const nextApp = next({ dev })
const handle = nextApp.getRequestHandler() //part of next config
const mongoose = require('mongoose')

const db = mongoose.connect(process.env.ENDPOINT, { useNewUrlParser: true, useUnifiedTopology: true })

nextApp.prepare().then(() => {
    // express code here
    const app = express()

    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({ extended: true }));
    app.use(fileUpload());

    console.log('loaded middleware');

    var pusher = new Pusher({
        appId: '940826',
        key: '830e71168fc6cf18d014',
        secret: 'a2e77b2e4ac20b31f540',
        cluster: 'us3',
        encrypted: true
    });
    module.exports = pusher

    app.use('/api/markers', require('./routes/api'));
    console.log('loaded marker routes')
    app.use('/upload/map', require('./routes/upload'));
    console.log('loaded upload routes');

    app.get('*', (req,res) => {
        return handle(req,res) // for all the react stuff
    })
    app.listen(PORT, err => {
        if (err) throw err;
        console.log(`ready at http://localhost:${PORT}`)
    })
})