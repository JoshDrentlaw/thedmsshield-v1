import 'express-fileupload'
import Cors from 'micro-cors'

const cors = Cors({
    allowedMethods: ['GET', 'HEAD', 'POST'],
})

const Upload = (req, res) => {
    if (req.method === 'POST') {
        if (!req.files || Object.keys(req.files).length === 0) {
            return res.status(400).send('No files were uploaded.');
        }
        else if (req.files) {
            let newMap = req.files.newMap;

            newMap.mv('/public/raimica_map.jpg', function (err) {
                if (err) {
                    return res.status(500).send(err);
                }

                res.send('File uploaded!');
            })
        }
    }
}

export default cors(Upload)