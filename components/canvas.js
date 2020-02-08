import React, { useState } from 'react'
import styled from 'styled-components'
import window from 'global'
//import interact from 'interactjs'
import { Icon, Menu, Segment, Sidebar, Popup } from 'semantic-ui-react'
import Pusher from 'pusher-js'

import Note from './note'
import Upload from './upload'
import MarkerEditor from './marker-editor'

const SCanvas = styled.div`
    width: 100%;
    height: calc(100vh - 100.406px);
    border-top: 7px solid black;
    border-left: 7px solid black;
    border-bottom: 2px solid black;
    border-right: 2px solid black;
    overflow: ${props => props.show ? 'hidden' : 'scroll'};
    position: relative;
    background-color: black;
    user-select: none;

    @media(min-width: 568px) {
        width: 100%;

        ::-webkit-scrollbar {
            width: 15px;
            height: 15px;
        }
    }

    ::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    ::-webkit-scrollbar-track {
        background-color: black;
    }

    ::-webkit-scrollbar-thumb,
    ::-webkit-scrollbar-thumb:hover {
        background-color: white;
        border: 1px solid white;
        border-radius: 0;
    }

    ::-webkit-scrollbar-corner {
        background-color: black;
    }
`


const Img = styled.img`
    zoom: ${props => props.zoom};
`

const Buttons = styled.div`
    display: flex;
    flex-direction: row;
    background-color: #171717;
`

const Column = styled.div`
    width: 100%;
    height: 150px;

    @media(min-width: 568px) {
        display: flex;
        justify-content: space-evenly;
        height: 75px;
    }
`

const Item = styled.div`
    color: ${props => props.pushed ? '#00a1ff' : 'white'};
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;

    @media(min-width: 568px) {
        padding: 1em;
        height: 100%;
    }

    & i:active, & i:hover {
        color: #00a1ff;
    }
`

const pusher = new Pusher(process.env.PUSHER_KEY, {
    cluster: 'us3',
    forceTLS: true
})

const channel = pusher.subscribe('markerChannel')

class Canvas extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            markers: this.props.markers,
            zoom: 0.5,
            opacity: 0,
            visible: false
        }

        this.image = React.createRef();
        this.marker = React.createRef();
    }

    componentDidMount() {
        let zoom;
        if (window.innerWidth >= 1024) {
            zoom = 1
        }
        else if (window.innerWidth >= 568) {
            zoom = 0.7
        }
        else {
            zoom = 0.5
        }
        this.setState({ zoom })

        /* channel.bind('markerAdded', data => {
            this.setState({ markers: [...this.state.markers, data]})
        }) */

        /* let self = this
        interact('.marker')
            .draggable({
                inertia: false,
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: 'parent',
                        endOnly: true
                    })
                ],
                autoScroll: true,
                onmove: function(event) {
                    console.log(event)
                    const { target, dx, dy } = event
                    // keep the dragged position in the x/y attributes
                    var x = (parseFloat(target.getAttribute('x')) || 0) + dx
                    var y = (parseFloat(target.getAttribute('y')) || 0) + dy
                    console.log({x, y, dx, dy})
                    console.log('translate x:', (x + (50 / 2)) * self.state.zoom)
                    console.log('translate y:', (y + (50 / 2)) * self.state.zoom)
                    // translate the element
                    target.style.transform =
                        `translate(
                            ${((x + (50 / 2)) * self.state.zoom)}px,
                            ${((y + (50 / 2)) * self.state.zoom)}px
                        )`

                    // update the posiion attributes
                    target.setAttribute('x', x)
                    target.setAttribute('y', y)
                },
                onend: function(e) {
                    console.log(e)
                }
            }) */
    }

    /* componentDidUpdate(prevProps, prevState) {
        if (this.state.markers !== prevState.markers) {
            
        }
    } */

    zoomIn = () => {
        if (this.state.zoom < 1) {
            this.setState({ zoom: parseFloat((this.state.zoom + 0.1)).toFixed(1) })

        }
    }

    zoomOut = () => {
        if (this.state.zoom > 0.1) {
            this.setState({ zoom: parseFloat((this.state.zoom - 0.1)).toFixed(1) })
        }
    }

    handleNewMarker = () => {
        fetch('/api/markers/new', {
            method: 'POST',
            headers: { "Content-Type": "application/json" }
        })
            .then(res => {
                return res.json()
            })
            .then(res => {
                //res = JSON.parse(res)
                this.setState({ markers: [...this.state.markers, res ] })
            })
    }

    render() {
        return (
            <>
                <Sidebar.Pushable as={Segment} style={{ margin: '-14px 0 0 !important',  overflow: 'hidden' }}>
                    <Sidebar.Pusher>
                        <SCanvas show={this.state.show}>
                            <Img ref={this.image} zoom={this.state.zoom} id="map" src="/raimica_map.jpg" />
                            {this.state.markers.map(marker => {
                                return (
                                    <Note
                                        key={marker._id}
                                        type={marker.type}
                                        title={marker.note_title}
                                        body={marker.note_body}
                                        zoom={this.state.zoom}
                                        top={parseFloat((marker.top * this.state.zoom)).toFixed(2)}
                                        left={parseFloat((marker.left * this.state.zoom)).toFixed(2)}
                                        width={parseFloat(((marker.width ? marker.width : 50) * this.state.zoom)).toFixed(2)}
                                        height={parseFloat(((marker.height ? marker.height : 50) * this.state.zoom)).toFixed(2)}
                                        _id={marker._id}
                                        opacity={this.state.opacity}
                                    />
                                )
                            })}
                        </SCanvas>
                    </Sidebar.Pusher>
                    <Sidebar
                        animation='overlay'
                        direction={window.innerWidth > 568 ? 'bottom' : 'left'}
                        onHide={() => this.setState({ visible: false })}
                        visible={this.state.visible}
                    >
                        <Buttons columns="3" textAlign="center" padded={true}>
                            <Column>
                                <Item onClick={() => (this.state.zoom < 1 ? this.setState({ zoom: parseFloat((this.state.zoom + 0.1)).toFixed(1) }) : false)}>
                                    <Icon className="zoomer" name="zoom-in" size="big" />
                                </Item>
                                <Item onClick={() => (this.state.zoom > 0.1 ? this.setState({ zoom: parseFloat((this.state.zoom - 0.1)).toFixed(1) }) : false)}>
                                    <Icon className="zoomer" name="zoom-out" size="big" />
                                </Item>
                            </Column>
                            <Column>
                                <Item pushed={this.state.opacity} onClick={() => this.setState({ opacity: 0.6 })}>
                                    <Icon name="eye" size="big" />
                                </Item>
                                <Item pushed={!this.state.opacity} onClick={() => this.setState({ opacity: 0 })}>
                                    <Icon name="eye slash" size="big" />
                                </Item>
                            </Column>
                            <Column>
                                <Item>
                                    <Icon name="plus" size="big" onClick={() => this.handleNewMarker()} />
                                </Item>
                                <Item>
                                    <Upload>
                                        <div>
                                            <Icon name="upload" size="big" />
                                        </div>
                                    </Upload>
                                </Item>
                            </Column>
                        </Buttons>
                    </Sidebar>
                </Sidebar.Pushable>
                <Menu as="nav" style={{ margin: '0px !important' }}>
                    <Menu.Item
                        name="Map Actions"
                        onClick={() => this.setState({ visible: !this.state.visible })}
                    />
                </Menu>
            </>
        )
    }
}

export default Canvas