import React, { useState, useRef } from 'react'
import styled from 'styled-components'
import { Button, Header, Form, Icon, Modal, Loader, Image } from 'semantic-ui-react'
import Axios from 'axios'

const Upload = (props) => {
    const [newMap, setNewMap] = useState(null)

    const handleChange = ({ target }) => {
        setNewMap(target.files[0])
        console.log(target.files[0].stream())
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        const formData = new FormData()
        formData.append('newMap', newMap)
        Axios.post('/upload/map', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(res => {
                if (res.status === 200) {
                    window.location.reload();
                }
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
                {
                    newMap ?
                        <Image src={newMap.name} size="medium" />
                        : null
                }
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