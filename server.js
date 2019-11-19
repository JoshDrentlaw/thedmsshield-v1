require('dotenv').config()
const express = require('express')
const next = require('next')
var mysql = require('mysql')

const app = next()
const handle = app.getRequestHandler()
console.log(handle)

var connection = mysql.createConnection({
    host: process.env.ENDPOINT,
    user: process.env.USERNAME,
    password: process.env.PASSWORD,
    database: process.env.DATABASE
})

connection.connect()

global.connection = connection

console.log(connection)

app.prepare().then(() => {
    console.log('prepared')
    const server = express()

    server.all('*', (req, res) => {
        return handle(req, res)
    })

    server.listen(port, err => {
        if (err) throw err
        console.log(`> Ready on http://localhost:${port}`)
    })
})