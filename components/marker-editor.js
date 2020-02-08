import React, { useState, useEffect } from 'react'
import styled from 'styled-components'
import { Form } from 'semantic-ui-react'
import Pusher from 'pusher-js'
import withPusher from 'react-pusher-hoc'
//import pnotify from 'pnotify/dist/es/PNotify'
//import PNotifyButtons from 'pnotify/dist/es/PNotifyButtons'

const SForm = styled(Form)`
    width: 80vw !important;

    @media(min-width: 1024px) {
        width: 20vw !important;
    }

    /* input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    } */
`

const pusher = new Pusher(process.env.PUSHER_KEY, {
    cluster: 'us3',
    forceTLS: true
});
const channel = pusher.subscribe('markerEditChannel')

const MarkerEditor = (props) => {
    const [mounted, setMounted] = useState(false)
    useEffect(() => {
        if (!mounted) {
            channel.bind('markerUpdated', function(data) {
                props.setTop(data.marker.top * props.zoom)
                props.setLeft(data.marker.left * props.zoom)
                props.setWidth(data.marker.width * props.zoom)
                props.setHeight(data.marker.height * props.zoom)
                props.setType(data.marker.type)
            })
            setMounted(true)
        }
    }, [mounted])

    const handleSave = () => {
        props.setEditMarker(false)
        const width = parseFloat((props.width / props.zoom).toFixed(2))
        const height = parseFloat((props.height / props.zoom).toFixed(2))
        const top = parseFloat((props.top / props.zoom).toFixed(2))
        const left = parseFloat((props.left / props.zoom).toFixed(2))

        fetch(`/api/markers/markerEditor/${props._id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ top, left, width, height, type: props.type })
        })
        .then(res => {
            return res.json()
        })
        .then(res => {
            console.log(res)
            /* pnotify.success({
                title: "Success!",
                text: "Note successfully updated!",
                Animate: {
                    animate: true,
                    inClass: 'flipInY',
                    outClass: 'flipOutY'
                }
            }) */
        })
    }

    const handleKeyDown = (event, setter) => {
        event.persist()
        const { key, ctrlKey, shiftKey, altKey, target: { value } } = event
        const up = key === 'ArrowUp'
        const down = key === 'ArrowDown'
        
        if (up && ctrlKey && shiftKey) {
            setter(parseFloat(value)+100)
        }
        else if (up && altKey && ctrlKey) {
            setter((parseFloat(value)+0.1).toFixed(2))
        }
        else if (up && altKey && shiftKey) {
            setter((parseFloat(value)+0.5).toFixed(2))
        }
        else if (up && ctrlKey) {
            setter(parseFloat(value)+5)
        }
        else if (up && shiftKey) {
            setter(parseFloat(value)+10) 
        }
        else if (up && altKey) {
            setter((parseFloat(value)+0.01).toFixed(2))
        }
        else if (up) {
            setter(parseFloat(value)+1)
        }
        else if (down && ctrlKey && shiftKey) {
            setter(parseFloat(value)-100)
        }
        else if (down && altKey && ctrlKey) {
            setter((parseFloat(value)-0.1).toFixed(2))
        }
        else if (down && altKey && shiftKey) {
            setter((parseFloat(value)-0.5).toFixed(2))
        }
        else if (down && ctrlKey) {
            setter(parseFloat(value)-5)
        }
        else if (down && shiftKey) {
            setter(parseFloat(value)-10) 
        }
        else if (down && altKey) {
            setter((parseFloat(value)-0.01).toFixed(2))
        }
        else if (down) {
            setter(parseFloat(value)-1)
        }
    }

    return (
        <SForm>
            <Form.Group inline>
                <Form.Radio
                    label='Circle'
                    value='circle'
                    checked={props.type === 'circle'}
                    onChange={() => props.setType('circle')}
                />
                <Form.Radio
                    label='Oval'
                    value='oval'
                    checked={props.type === 'oval'}
                    onChange={() => props.setType('oval')}
                />
            </Form.Group>
            {props.type === 'circle' ?
                <Form.Group inline unstackable widths="2">
                    <Form.Input
                        placeholder="Enter radius"
                        label="Radius"
                        fluid
                        type="number"
                        value={props.width}
                        onKeyDown={(event) => {handleKeyDown(event, props.setWidth);handleKeyDown(event, props.setHeight)}}//props.setWidth(parseFloat(value).toFixed(2));props.setHeight(parseFloat(value).toFixed(2))
                    />
                </Form.Group>
            :
                <Form.Group inline unstackable widths='2'>
                    <Form.Input
                        placeholder="Enter width"
                        label="Width"
                        fluid
                        type="number"
                        value={props.width}
                        onKeyDown={(event) => handleKeyDown(event, props.setWidth)}
                    />
                    <Form.Input
                        placeholder="Enter height"
                        label="Height"
                        fluid
                        type="number"
                        value={props.height}
                        onKeyDown={(event) => handleKeyDown(event, props.setHeight)}
                    />
                </Form.Group>
            }
            <Form.Group inline unstackable widths='2'>
                <Form.Input
                    placeholder="Enter X"
                    label="X"
                    fluid
                    type="number"
                    value={props.left}
                    onKeyDown={(event) => handleKeyDown(event, props.setLeft)}
                />
                <Form.Input
                    placeholder="Enter Y"
                    label="Y"
                    fluid
                    type="number"
                    value={props.top}
                    onKeyDown={(event) => handleKeyDown(event, props.setTop)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Button fluid icon="save" color="blue" content="Save" onClick={handleSave} />
            </Form.Group>
        </SForm>
    )
}

const mapEventsToProps = {
    mapPropsToValues: props => ({
        top: props.top,
        setTop: props.setTop,
        left: props.left,
        setLeft: props.setLeft,
        width: props.width,
        setWidth: props.setWidth,
        height: props.height,
        setHeight: props.setHeight,
        type: props.type,
        setType: props.setType,
        editMarker: props.editMarker,
        setEditMarker: props.setEditMarker,
        zoom: props.zoom,
        _id: props._id
    }),
    events: {
        'marker-channel.marker-updated': (marker, state, props) => {
            console.log(marker)
            console.log('props.top:', props.top, 'marker.top:', marker.top)
            /* setTop(marker.top * props.zoom)
            setLeft(marker.left * props.zoom)
            setWidth(marker.width * props.zoom)
            setHeight(marker.height * props.zoom)
            setTitle(marker.note_title)
            const content = convertFromRaw(JSON.parse(marker.note_body))
            setEditorState(content)
            setType(marker.type) */
        }
    }
}

export default MarkerEditor