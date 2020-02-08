const express = require('express')
const router = express.Router()
const Markers = require('../models/markerModel')
const pusher = require('../index')
const uuidv1 = require('uuid/v1')

router.get('/', (req, res) => {
    Markers.find({}, (err, markers) => {
        res.json(markers)
    })
})

router
    .post('/new', (req, res) => {
        const markerProps = {
            top: 20, left: 20,
            width: 40, height: 40,
            type: 'circle',
            note_title: 'New Marker',
            note_body: JSON.stringify({"blocks":[{"key":uuidv1().split('-').pop().slice(0, 5).toString(),"text":"","type":"unstyled","depth":0,"inlineStyleRanges":[],"entityRanges":[],"data":{}}],"entityMap":{}})
        }
        const newMarker = new Markers(markerProps)
        newMarker.save().then(marker => {
            /* pusher.trigger('markerChannel', 'markerAdded', {
                "marker": marker
            }) */
            res.json(marker)
        })
    })

router.use('/markerEditor/:id', (req, res, next) => {
    Markers.findById(req.params.id, (err, marker) => {
        if(err)
            res.status(500).send(err)
        else 
            req.marker = marker 
            next()
    })
})
router
    .get('/markerEditor/:id', (req, res) => {
        return res.json( req.marker )
    })
    .put('/markerEditor/:id', (req, res) =>{
        Object.keys(req.body).map(key => {
            req.marker[key] = req.body[key]
        })
        req.marker.save()
        pusher.trigger('markerEditChannel', 'markerUpdated', {
            "marker": req.marker
        });
        res.json(req.marker)
    })

router.use('/noteEditor/:id', (req, res, next) => {
    Markers.findById(req.params.id, (err, marker) => {
        if(err)
            res.status(500).send(err)
        else 
            req.marker = marker 
            next()
    })
})
router
    .get('/noteEditor/:id', (req, res) => {
        return res.json( req.marker )
    })
    .put('/noteEditor/:id', (req, res) =>{
        Object.keys(req.body).map(key => {
            req.marker[key] = req.body[key]
        })
        req.marker.save()
        pusher.trigger('noteEditChannel', 'noteUpdated', {
            "marker": req.marker
        });
        res.json(req.marker)
    })
module.exports = router;