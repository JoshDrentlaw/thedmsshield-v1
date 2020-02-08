const mongoose = require('mongoose')
const schema = mongoose.Schema

const markerModel = new schema({
    top: { type: Number } ,
    left: { type: Number },
    width: { type: Number },
    height: { type: Number },
    type: { type: String },
    note_title: { type: String },
    note_body: { type: String }
})

module.exports = mongoose.model('raimica_markers', markerModel)