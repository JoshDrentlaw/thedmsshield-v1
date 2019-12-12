import React, { useState, useRef } from 'react'
import styled from 'styled-components'
import { Button, Header, Form, Icon, Modal, Loader } from 'semantic-ui-react'
import Axios from 'axios'

const Upload = (props) => {
    const [img, setImg] = useState(null)

    const handleChange = ({ target }) => {
        setImg(target.value)
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        Axios.post('/api/uploadMap', img)
            .then(res => {
                console.log(res)
            })
    }

    return (
        <Modal trigger={props.children} size='small'>
            <Header icon='upload' content='Upload New Raimica Map' />
            <Modal.Content>
                <Form
                    id='uploadForm'
                    encType="multipart/form-data"
                >
                    <Form.Input type="file" name="newMap" onChange={handleChange} />
                </Form>
            </Modal.Content>
            <Modal.Actions>
                <Button color='red'>
                    <Icon name='remove' /> Cancel
                </Button>
                <Button onClick={handleSubmit} color='green'>
                    <Icon name='checkmark' /> Submit
                </Button>
            </Modal.Actions>
        </Modal>
    )
}

export default Upload