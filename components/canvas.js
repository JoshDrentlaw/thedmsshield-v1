import React, { useState } from 'react'
import styled from 'styled-components'
import window from 'global'
import interact from 'interactjs'
import { Icon, Menu, Segment, Sidebar } from 'semantic-ui-react'

import Note from './note'
import Upload from './upload'

const SCanvas = styled.div`
    width: 100%;
    height: calc(75% - 34px);
    border-top: 7px solid black;
    border-left: 7px solid black;
    border-bottom: 2px solid black;
    border-right: 2px solid black;
    overflow: ${props => props.show ? 'hidden' : 'scroll'};
    position: relative;
    background-color: black;
    margin-top: -1rem;
    margin-bottom: 0.75em;
    user-select: none;

    @media(min-width: 568px) {
        width: calc(100% - 65px);
        height: calc(100vh - 53.41px);
        margin: -14px 0 0 65px;
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

const Marker = styled.div.attrs(props => ({
    x: props.left - (props.width / 2),
    y: props.top - (props.height / 2)
}))`
    top: ${props => props.y}px;
    left: ${props => props.x}px;
    width: ${props => props.width || 50}px;
    height: ${props => props.height || 50}px;
    border-radius: 999999px;
    background-color: black;
    position: absolute;
    transition: opacity 300ms cubic-bezier(.18,.03,.83,.95);
    opacity: ${props => props.opacity};
    touch-action: none;
    user-select: none;

    &:hover {
        cursor: pointer;
    }
`

const Buttons = styled.div`
    display: flex;
    flex-direction: row;

    @media(min-width: 568px) {
        flex-direction: column;
        position: fixed;
        top: 53px;
        left: 0px;
        width: 65px;
        background-color: black;
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
            opacity: 0
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

        let self = this
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
            })
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

    render() {
        return (
            <>
                <SCanvas show={this.state.show}>
                    <Img ref={this.image} zoom={this.state.zoom} id="map" src="/raimica_map.jpg" />
                    {this.props.markers.map(marker => {
                        return (
                            <Note key={marker._id} title={marker.note_title} body={marker.note_body} _id={marker._id}>
                                <Marker
                                    top={marker.top * this.state.zoom}
                                    left={marker.left * this.state.zoom}
                                    width={(marker.width ? marker.width : 50) * this.state.zoom}
                                    height={(marker.height ? marker.height : 50) * this.state.zoom}
                                    opacity={this.state.opacity}
                                    onMouseDown={this.handleDrag}
                                    ref={this.marker}
                                    className="marker"
                                />
                            </Note>

                        )
                    })}
                </SCanvas>
                <Buttons columns="3" textAlign="center" padded={true}>
                    <Column>
                        <Item onClick={() => (this.state.zoom < 1 ? this.setState({ zoom: this.state.zoom + 0.1 }) : false)}>
                            <Icon className="zoomer" name="zoom-in" size="big" />
                        </Item>
                        <Item onClick={() => (this.state.zoom > 0.1 ? this.setState({ zoom: this.state.zoom - 0.1 }) : false)}>
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
            </>
        )
    }
}

export default Canvas