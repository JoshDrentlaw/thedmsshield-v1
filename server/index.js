require('dotenv').config()
const express = require('express')
const next = require('next')
const bodyParser = require('body-parser')
const fileUpload = require('express-fileupload')

const PORT = process.env.PORT || 3000
const dev = process.env.DEV !== 'production' //true false
console.log(process.env)
console.log(dev)

const nextApp = next({ dev })
console.log(nextApp)
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