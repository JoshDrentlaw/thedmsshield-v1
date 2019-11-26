import React from 'react'
import styled from 'styled-components'
import window from 'global'

import ZoomIn from '../public/svg/zoom-in.svg'
import ZoomOut from '../public/svg/zoom-out.svg'
import Show from '../public/svg/show.svg'
import Hide from '../public/svg/hide.svg'

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

    svg {
        width: 40%;
        height: 40%;
    }
`

const ShowIcon = styled(Show)`

`

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
                                data-id={marker._id}
                                top={marker.top*this.state.zoom}
                                left={marker.left*this.state.zoom}
                                radius={50*this.state.zoom}
                                opacity={this.state.opacity}
                            />
                        )
                    })}
                </SCanvas>
                <Buttons>
                    <ButtonContainer>
                        <IconWrapper onClick={() => (this.state.zoom < 1 ? this.setState({ zoom: this.state.zoom + 0.1 }) : false)}>
                            <ZoomIn />
                        </IconWrapper>
                        <IconWrapper onClick={() => (this.state.zoom > 0.1 ? this.setState({ zoom: this.state.zoom - 0.1 }) : false)}>
                            <ZoomOut />
                        </IconWrapper>
                    </ButtonContainer>
                    <ButtonContainer>
                        <IconWrapper onClick={() => this.setState({ opacity: 1 })}>
                            <Show  />
                        </IconWrapper>
                        <IconWrapper onClick={() => this.setState({ opacity: 0 })}>
                            <Hide />
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