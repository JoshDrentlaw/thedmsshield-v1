import React from 'react'
import styled from 'styled-components'

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

class Canvas extends React.Component {
    constructor(props) {
        super(props)

        this.getCursor = this.getCursor.bind(this);
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
        return (
            <SCanvas onClick={this.getCursor}>
                <Img src="/raimica_map.jpg" />
            </SCanvas>
        )
    }
}

export default Canvas