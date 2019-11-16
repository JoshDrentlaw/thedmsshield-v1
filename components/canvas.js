import React from 'react'
import styled from 'styled-components'

const SCanvas = styled.div`
    width: 100%;
    height: 85%;
    border: 2px solid #424242;
    overflow: scroll;
    position: relative;
`

const Img = styled.img`
    transform: scale(0.5);
`

class Canvas extends React.Component {
    constructor(props) {
        super(props)
    }

    componentDidMount() {
        
    }

    render() {
        return (
            <SCanvas>
                <Img src="/raimica_map.jpg" />
            </SCanvas>
        )
    }
}

export default Canvas