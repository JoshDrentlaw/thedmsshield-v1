const express = require('express')
const router = express.Router()
const Markers = require('../models/markerModel')

router.get('/', (req, res) => {
    Markers.find({}, (err, markers) => {
        res.json(markers)
    })
})
module.exports = router;