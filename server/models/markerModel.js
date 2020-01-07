const mongoose = require('mongoose')
const schema = mongoose.Schema

const markerModel = new schema({
    top: { type: Number } ,
    left: { type: Number },
    width: { type: Number },
    height: { type: Number },
    note_title: { type: String },
    note_body: { type: {} }
})

module.exports = mongoose.model('raimica_markers', markerModel)