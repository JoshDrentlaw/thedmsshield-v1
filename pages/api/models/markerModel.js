import mongoose from 'mongoose'

const schema = mongoose.Schema

const markerModel = new schema({
    top: { type: Number } ,
    left: { type: Number },
    note_title: { type: String },
    note_body: { type: {} }
})

export default mongoose.model('raimica_markers', markerModel)