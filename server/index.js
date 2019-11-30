require('dotenv').config()
const express = require('express')
const next = require('next')
const bodyParser = require('body-parser')

const PORT = process.env.PORT || 3000
const app = next({ dev: process.env.NODE_ENV !== 'production' });
const handler = routes.getRequestHandler(app);
const env = process.env.NODE_ENV || 'dev';

const mongoose = require('mongoose')
const db = mongoose.connect(process.env.ENDPOINT, { useNewUrlParser: true, useUnifiedTopology: true })

nextApp.prepare().then(() => {
    // express code here
    const app = express()

    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({ extended: true }));
    app.use('/api/markers', require('./routes/index')) 

    app.get('*', (req,res) => {
        return handle(req,res) // for all the react stuff
    })
    app.listen(PORT, err => {
        if (err) throw err;
        console.log(`ready at http://localhost:${PORT}`)
    })
})