require('dotenv').config()
const express = require('express')
const next = require('next')
var mysql = require('mysql')

const app = next()
const handle = app.getRequestHandler()

var connection = mysql.createConnection({
    host: process.env.ENDPOINT,
    user: process.env.USERNAME,
    password: process.env.PASSWORD,
    database: process.env.DATABASE
})

connection.connect()

global.connection = connection

app.prepare().then(() => {
    const server = express()

    server.all('*', (req, res) => {
        return handle(req, res)
    })

    server.listen(port, err => {
        if (err) throw err
        console.log(`> Ready on http://localhost:${port}`)
    })
})