const express = require('express')
const router = express.Router()
const Markers = require('../models/markerModel')

router.get('/', (req, res) => {
    Markers.find({}, (err, markers) => {
        res.json(markers)
    })
})

router.use('/:id', (req, res, next) => {
    Markers.findById(req.params.id, (err, marker) => {
        if(err)
            res.status(500).send(err)
        else 
            req.marker = marker 
            next()
    })
})
router
    .get('/:id', (req, res) => {
        return res.json( req.marker )
    })
    .post('/:id', (req, res) =>{
        Object.keys(req.body).map(key=>{
            req.marker[key] = req.body[key]
        })
        req.marker.save()
        res.json(req.marker)
    })
module.exports = router;