import React, { useState } from 'react'
import styled from 'styled-components'
import { Form } from 'semantic-ui-react'

const SForm = styled(Form)`
    width: 80vw !important;

    /* input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    } */
`

const MarkerEditor = (props) => {
    const [step, setStep] = useState(1)
    const [type, setType] = useState(props.type)

    const handleSave = () => {
        props.setEditMarker(false)
        const width = props.width / props.zoom
        const height = props.height / props.zoom
        const top = props.top / props.zoom
        const left = props.left / props.zoom
        fetch(`/api/markers/${props._id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ top, left, width, height, type })
        })
        .then(res => {
            console.log(res)
            const { status } = res
            if (status === 200) {
                /* PNotify.success({
                    title: "Success!",
                    text: "Note successfully updated!",
                    Animate: {
                        animate: true,
                        inClass: 'flipInY',
                        outClass: 'flipOutY'
                    }
                }) */
            }
        })
    }

    return (
        <SForm>
            <Form.Group inline>
                <Form.Radio
                    label='Circle'
                    value='circle'
                    checked={type === 'circle'}
                    onChange={() => setType('circle')}
                />
                <Form.Radio
                    label='Oval'
                    value='oval'
                    checked={type === 'oval'}
                    onChange={() => setType('oval')}
                />
            </Form.Group>
            {type === 'circle' ?
                <Form.Group inline unstackable widths="2">
                    <Form.Input
                        placeholder="Enter radius"
                        label="Radius"
                        fluid
                        type="number"
                        value={props.width}
                        onChange={({target: { value }}) => {props.setWidth(value);props.setHeight(value)}}
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
                        onChange={({target: { value }}) => props.setWidth(value)}
                    />
                    <Form.Input
                        placeholder="Enter height"
                        label="Height"
                        fluid
                        type="number"
                        value={props.height}
                        onChange={({target: { value }}) => props.setHeight(value)}
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
                    onChange={({target: { value }}) => props.setLeft(value)}
                />
                <Form.Input
                    placeholder="Enter Y"
                    label="Y"
                    fluid
                    type="number"
                    value={props.top}
                    onChange={({target: { value }}) => props.setTop(value)}
                />
            </Form.Group>
            <Form.Group>
                <Form.Button fluid icon="save" color="blue" content="Save" onClick={handleSave} />
            </Form.Group>
        </SForm>
    )
}

export default MarkerEditor