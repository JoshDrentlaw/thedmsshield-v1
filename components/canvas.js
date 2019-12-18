import React from 'react'
import styled from 'styled-components'
import window from 'global'
import { Icon, Grid, Button } from 'semantic-ui-react'

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
    margin-bottom: 0.75em;

    @media(min-width: 568px) {
        width: 100%;
        height: calc(100vh - 53.41px);
        margin: -14px 0 0;
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
    x: props.left - (props.radius / 2),
    y: props.top - (props.radius / 2)
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

const Buttons = styled.div`
    display: flex;
    flex-direction: row;

    @media(min-width: 568px) {
        position: fixed;
        top: 60px;
        left: 8px;
        background-color: beige;
        opacity: 0.2;
        transition: opacity 0.2s ease-in;

        &:hover {
            opacity: 1;
        }
    }

    @media(min-width: 1024px) {
        flex-direction: column;
    }
`

const Column = styled.div`
    width: 100%;
    height: 150px;

    @media(min-width: 568px) {
        display: flex;
        height: 60px;
    }

    @media(min-width: 1024px) {
        flex-direction: column;
        height: 150px;
    }
`

const Item = styled.div`
    text-shadow: ${props => props.pushed ? '0px 0px 13px #00a1ff' : 'none'};
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;

    @media(min-width: 568px) {
        padding: 1em;
        height: 100%;
    }

    & i:active, & i:hover {
        text-shadow: 0px 0px 13px #00a1ff;
    }
`

class Canvas extends React.Component {
    constructor(props) {
        super(props)
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

        this.state = {
            zoom: zoom,
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
                                    radius={50 * this.state.zoom}
                                    opacity={this.state.opacity}
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
                        <Item pushed={this.state.opacity} onClick={() => this.setState({ opacity: 1 })}>
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