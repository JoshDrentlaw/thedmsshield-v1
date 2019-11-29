import React, { useState, useEffect } from 'react'
import styled from 'styled-components'
import window from 'global'
import fetch from 'isomorphic-unfetch'
import { Button, Form, TextArea, Icon, Modal } from 'semantic-ui-react'

const SCanvas = styled.div`
    width: 100%;
    height: calc(75% - 36px);
    border: 2px solid #424242;
    overflow: ${props => props.show ? 'hidden' : 'scroll'};
    position: relative;
`

const Img = styled.img`
    zoom: ${props => props.zoom};
`

const Marker = styled.div.attrs(props => ({
    x: props.left - (props.radius/2),
    y: props.top - (props.radius/2)
}))`
    top: ${props => props.y}px;
    left: ${props => props.x}px;
    width: ${props => props.radius}px;
    height: ${props => props.radius}px;
    border-radius: 999999px;
    background-color: black;
    position: absolute;
    transition: opacity 300ms cubic-bezier(.18,.03,.83,.95);
    opacity: ${props => props.opacity};
`

const Buttons = styled.section`
    width: 100%;
    height: calc(25% - 36px);
    display: flex;
    border: 1px solid black;
`

const ButtonContainer = styled.div`
    width: 33.33333333%;
    height: 100%;
    display: flex;
    flex-direction: column;
`

const IconWrapper = styled.div`
    width: 100%;
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #a3a3a3;

    i {
        text-shadow: ${props => props.active ? '0px 0px 13px #00a1ff' : 'none'};
    }

    i.zoomer:active, i.zoomer:hover {
        text-shadow: 0px 0px 13px #00a1ff;
    }
`

const Note = (props) => {
    const [edit, setEdit] = useState(false);
    const [title, setTitle] = useState(props.title);
    const [body, setBody] = useState(props.body);

    /* useEffect(() => {
        if (open && !edit) {
            console.log('saving')
            
        }
    }, [edit]) */

    const saveNote = () => {
        const url =  (process.env.NODE_ENV === 'production' ? `http://thedmsshield.com:3000/api/getMarkers/${props._id}` : `http://localhost:3000/api/markers/${props._id}`)
        if (title !== props.title || body !== props.body) {
            fetch(`http://thedmsshield.com:3000/api/getMarkers/${props._id}`, {
                method: 'PUT',
                headers: {
                'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({note_title: title, note_body: body})
            })
        }
        setEdit(!edit)
    }

    return (
        <Modal
            trigger={props.children}
            dimmer="inverted"
            closeOnEscape={edit ? false : true}
            closeOnDimmerClick={edit ? false : true}
            closeIcon={edit ? false : true}
        >
            <Modal.Header>
                {edit ?
                    <Form.Input value={title} onChange={({ target }) => setTitle(target.value)} />
                    : title
                }
            </Modal.Header>
            <Modal.Content>
                <Modal.Description>
                    {edit ?
                        <Form><TextArea value={body} onChange={({ target }) => setBody(target.value)} /></Form>
                        : <p>{body}</p>
                    }
                    
                </Modal.Description>
            </Modal.Content>
            <Modal.Actions>
                <Button color="blue" onClick={() => saveNote()}>
                    <Icon name={edit ? "save" : "edit"} />
                    {edit ? "Save" : "Edit"}
                </Button>
            </Modal.Actions>
        </Modal>
        
    )
}

class Canvas extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            zoom: 0.7,
            opacity: 0
        }

        this.image = React.createRef();
    }

    componentDidMount() {
        const zoom = parseFloat(window.getComputedStyle(this.image.current).zoom)
        this.setState({ zoom })
    }

    zoomIn = () => {
        if (this.state.zoom < 1) {
            this.setState({ zoom: this.state.zoom + 0.1 })

        }
    }

    zoomOut = () => {
        if (this.state.zoom > 0.1) {
            this.setState({ zoom: this.state.zoom - 0.1 })
        }
    }

    saveNote = (marker) => {
        const url =  (process.env.NODE_ENV === 'production' ? `http://thedmsshield.com:3000/api/getMarkers/${id}` : `http://localhost:3000/api/markers/${id}`)
        fetch(url, {
            method: 'PUT',
            headers: {
            'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(marker)
        })
    }

    render() {
        return (
            <>
                <SCanvas show={this.state.show}>
                    <Img ref={this.image} zoom={this.state.zoom} id="map" src="/raimica_map.jpg" />
                    {this.props.markers.map(marker => {
                        return (
                            <Note key={marker._id} title={marker.note_title} body={marker.note_body} _id={marker._id}>
                                <Marker
                                    top={marker.top*this.state.zoom}
                                    left={marker.left*this.state.zoom}
                                    radius={50*this.state.zoom}
                                    opacity={this.state.opacity}
                                />
                            </Note>
                            
                        )
                    })}
                </SCanvas>
                <Buttons>
                    <ButtonContainer>
                        <IconWrapper onClick={() => (this.state.zoom < 1 ? this.setState({ zoom: this.state.zoom + 0.1 }) : false)}>
                            <Icon className="zoomer" name="zoom-in" size="big" />
                        </IconWrapper>
                        <IconWrapper onClick={() => (this.state.zoom > 0.1 ? this.setState({ zoom: this.state.zoom - 0.1 }) : false)}>
                            <Icon className="zoomer" name="zoom-out" size="big" />
                        </IconWrapper>
                    </ButtonContainer>
                    <ButtonContainer>
                        <IconWrapper onClick={() => this.setState({ opacity: 1 })} active={this.state.opacity}>
                            <Icon name="eye" size="big" />
                        </IconWrapper>
                        <IconWrapper onClick={() => this.setState({ opacity: 0 })} active={!this.state.opacity}>
                            <Icon name="eye slash" size="big" />
                        </IconWrapper>
                    </ButtonContainer>
                    <ButtonContainer>
                        <IconWrapper>
                            
                        </IconWrapper>
                        <IconWrapper>
                            
                        </IconWrapper>
                    </ButtonContainer>
                </Buttons>
            </>
        )
    }
}

export default Canvas