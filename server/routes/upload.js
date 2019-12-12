const express = require('express')
const router = express.Router()

router.post('/', function(req, res) {
    if (!req.files || Object.keys(req.files).length === 0) {
        return res.status(400).send('No files were uploaded.');
    }

    let newMap = req.files.newMap;

    newMap.mv('/public/raimica_map.jpg', function(err) {
        if (err) {
            return res.status(500).send(err);
        }

        res.send('File uploaded!');
    })
})
module.exports = router;