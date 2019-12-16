import React, { useState, useRef } from 'react'
import styled from 'styled-components'
import { Button, Header, Form, Icon, Modal, Loader, Image } from 'semantic-ui-react'
import Axios from 'axios'

const Upload = (props) => {
    const [newMap, setNewMap] = useState(null)
    const [preview, setPreview] = useState(null)
    const [loading, setLoading] = useState(false)

    const handleChange = ({ target }) => {
        setNewMap(target.files[0])
        handlePreview(target.files[0])
    }

    const handlePreview = (file) => {
        const reader = new FileReader()
        reader.onload = () => {
            setPreview(reader.result)
        }
        reader.readAsDataURL(file)
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        setLoading(true)
        const formData = new FormData()
        formData.append('newMap', newMap)
        Axios.post('/upload/map', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
            .then(res => {
                if (res.status === 200) {
                    setLoading(false)
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
                {
                    newMap ?
                        <Image children={loading ? <Loader /> : null} src={preview} size="large" centered />
                        : null
                }
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