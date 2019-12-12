import withConnect from './connection'
import Markers from './models/markerModel'
import Cors from 'micro-cors'

const allMarkers = Markers

const cors = Cors({
    allowedMethods: ['GET', 'HEAD', 'PUT'],
})

function AllMarkers(req, res) {
    switch (req.method) {
        case 'GET':
            allMarkers.find({}, (err, markers) => {
                res.json(markers)
            })
            break;
        case 'POST':
            allMarkers.findById(req.body.id, (err, marker) => {
                Object.keys(req.body.data).map(key => {
                    marker[key] = req.body.data[key]
                })
                marker.save()
                res.json(marker)
            })
            break;
        default:
            allMarkers.find({}, (err, markers) => {
                res.json(markers)
            })
    }
    if (req.method === 'GET') {
        
    }
}

export default cors(withConnect(AllMarkers))