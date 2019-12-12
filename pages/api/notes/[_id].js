import Markers from '../models/markerModel'
import withConnect from '../connection'
import Cors from 'micro-cors'

const cors = Cors({
    allowedMethods: ['PUT'],
})

function SaveNote(req, res) {
    const {
        query: { _id }
    } = req
    
    Markers.findById(_id, (err, marker) => {
        Object.keys(req.body).map(key => {
            marker[key] = req.body[key]
        })
        marker.save()
        res.json(marker)
    })
}

export default cors(withConnect(SaveNote))