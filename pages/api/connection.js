import mongoose from 'mongoose'
import Markers from './models/markerModel'

const withConnect = handler => async (req, res) => {
    if (mongoose.connections[0].readyState !== 1) {
        // Using new database connection
        await mongoose.connect(process.env.ENDPOINT, {
            useNewUrlParser: true,
            useFindAndModify: false,
            useCreateIndex: true,
            useUnifiedTopology: true,
            bufferCommands: false
        });
    }
    return handler(req, res)
}

export default withConnect