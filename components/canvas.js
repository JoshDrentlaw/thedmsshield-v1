import React, { useState } from 'react'
import styled from 'styled-components'
import window from 'global'
//import interact from 'interactjs'
import { Icon, Menu, Segment, Sidebar } from 'semantic-ui-react'

import Note from './note'
import Upload from './upload'

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

    @media(min-width: 568px) {
        flex-direction: column;
        width: 65px;
        color: white;
    }
`

const Column = styled.div`
    width: 100%;
    height: 150px;

    @media(min-width: 568px) {
        display: flex;
        flex-direction: column;
        height: 150px;
    }
`

const Item = styled.div`
    color: ${props => props.pushed ? '#00a1ff' : 'white'};
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;

    @media(min-width: 568px) {
        padding: 1em;
        height: 100%;
    }

    & i:active, & i:hover {
        color: #00a1ff;
    }
`

class Canvas extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
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

    zoomIn = () => {
        if (this.state.zoom < 1) {
            this.setState({ zoom: parseFloat((this.state.zoom + 0.1).toFixed(1)) })

        }
    }

    zoomOut = () => {
        if (this.state.zoom > 0.1) {
            this.setState({ zoom: parseFloat((this.state.zoom - 0.1).toFixed(1)) })
        }
    }

    render() {
        return (
            <>
                <Sidebar.Pushable as={Segment} style={{ margin: '-14px 0 0 !important',  overflow: 'hidden' }}>
                    <Sidebar.Pusher>
                        <SCanvas show={this.state.show}>
                            <Img ref={this.image} zoom={this.state.zoom} id="map" src="/raimica_map.jpg" />
                            {this.props.markers.map(marker => {
                                return (
                                    <Note
                                        key={marker._id}
                                        type={marker.type}
                                        title={marker.note_title}
                                        body={marker.note_body}
                                        zoom={this.state.zoom}
                                        top={marker.top * this.state.zoom}
                                        left={marker.left * this.state.zoom}
                                        width={(marker.width ? marker.width : 50) * this.state.zoom}
                                        height={(marker.height ? marker.height : 50) * this.state.zoom}
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
                                <Item onClick={() => (this.state.zoom < 1 ? this.setState({ zoom: parseFloat((this.state.zoom + 0.1).toFixed(1)) }) : false)}>
                                    <Icon className="zoomer" name="zoom-in" size="big" />
                                </Item>
                                <Item onClick={() => (this.state.zoom > 0.1 ? this.setState({ zoom: parseFloat((this.state.zoom - 0.1).toFixed(1)) }) : false)}>
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
                                    <Icon name="plus" size="big" />
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