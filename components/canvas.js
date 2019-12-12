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

const Buttons = styled(Grid)`
    /* width: 50%;
    display: flex; */
`

const Row = styled(Grid.Row)`
    /* width: 33.33333333%;
    display: flex;
    flex-direction: column; */
    height: 75px;
`

const Column = styled(Grid.Column)`
    width: 100%;
    height: 100%;
    /* display: flex;
    justify-content: center;
    align-items: center; */
`

const Item = styled(({pushed, ...props}) => <Icon {...props} />)`
    text-shadow: ${props => props.pushed ? '0px 0px 13px #00a1ff' : 'none'};
    height: 100%;

    &.zoomer:active, &.zoomer:hover {
        text-shadow: 0px 0px 13px #00a1ff;
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
                    <Row>
                        <Column onClick={() => (this.state.zoom < 1 ? this.setState({ zoom: this.state.zoom + 0.1 }) : false)}>
                            <Item className="zoomer" name="zoom-in" size="big" />
                        </Column>
                        <Column onClick={() => this.setState({ opacity: 1 })}>
                            <Item name="eye" size="big" pushed={this.state.opacity} />
                        </Column>
                        <Column>
                            <Item name="plus" size="big" />
                        </Column>
                    </Row>
                    <Row>
                        <Column onClick={() => (this.state.zoom > 0.1 ? this.setState({ zoom: this.state.zoom - 0.1 }) : false)}>
                            <Item className="zoomer" name="zoom-out" size="big" />
                        </Column>
                        <Column onClick={() => this.setState({ opacity: 0 })}>
                            <Item name="eye slash" size="big" pushed={!this.state.opacity} />
                        </Column>
                        <Column>
                            <Upload>
                                <div>
                                    <Item name="upload" size="big" />
                                </div>
                            </Upload>
                        </Column>
                    </Row>
                </Buttons>
            </>
        )
    }
}

export default Canvas