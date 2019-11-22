import React from 'react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'

const SCanvas = styled.div`
    width: 100%;
    height: 75%;
    border: 2px solid #424242;
    overflow: scroll;
    position: relative;
`

const Img = styled.img`
    zoom: 0.5;
`

const Marker = styled.div`
    top: ${props => props.top};
    left: ${props => props.left};
    width: 1rem;
    height: 1rem;
    border-radius: 999999px;
    background-color: black;
    position: absolute;
`

class Canvas extends React.Component {
    constructor(props) {
        super(props)
    }

    componentDidMount() {
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

    render() {
        let zoom;
        /* if (typeof window !== undefined) {
            zoom = document.querySelector('#map').style.zoom;
        } */
        return (
            <SCanvas onClick={() => this.getCursor()}>
                <Img id="map" src="/raimica_map.jpg" />
                {this.props.markers.map(marker => {
                    return <Marker
                                key={marker._id}
                                data-title={marker.note_title}
                                data-body={marker.note_body}
                                top={marker.top*zoom}
                                left={marker.left*zoom}
                            />
                })}
            </SCanvas>
        )
    }
}

export default Canvas