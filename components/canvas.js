import React from 'react'
import styled from 'styled-components'
import window from 'global'

import ZoomIn from '../public/svg/zoom-in.svg'
import ZoomOut from '../public/svg/zoom-out.svg'

const SCanvas = styled.div`
    width: 100%;
    height: calc(75% - 36px);
    border: 2px solid #424242;
    overflow: scroll;
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

    svg {
        width: 40%;
        height: 40%;
    }
`

class Canvas extends React.Component {
    constructor(props) {
        super(props)

        this.state = { zoom: 1 }

        this.image = React.createRef();
    }

    componentDidMount() {
        const zoom = window.getComputedStyle(this.image.current).zoom
        this.setState({ zoom })
    }

    getCursor = (props) => {
        /* console.log(event)
        const parent = target.parentElement
        const [offsetLeft, offsetTop] = [parent.offsetLeft, parent.offsetTop]
        const [left, top] = [parent.scrollLeft, parent.scrollTop]
        //const [scrollW, scrollH] = [parent.scrollWidth, parent.scrollHeight]
        const [y, x] = [(offsetLeft + left + (clientX - left)), (offsetTop + top + (clientY - top))] */
        console.log(event.offsetX, event.offsetY)
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
                <SCanvas onClick={() => this.getCursor()}>
                    <Img ref={this.image} zoom={this.state.zoom} id="map" src="/raimica_map.jpg" />
                    {this.props.markers.map(marker => {
                        return (
                            <Marker
                                key={marker._id}
                                data-title={marker.note_title}
                                data-body={marker.note_body}
                                top={marker.top*this.state.zoom}
                                left={marker.left*this.state.zoom}
                                radius={50*this.state.zoom}
                            />
                        )
                    })}
                </SCanvas>
                <Buttons>
                    <ButtonContainer>
                        <IconWrapper onClick={() => this.zoomIn()}>
                            <ZoomIn />
                        </IconWrapper>
                        <IconWrapper onClick={() => this.zoomOut()}>
                            <ZoomOut />
                        </IconWrapper>
                    </ButtonContainer>
                    <ButtonContainer></ButtonContainer>
                    <ButtonContainer></ButtonContainer>
                </Buttons>
            </>
        )
    }
}

export default Canvas