import React from 'react'
import { Container, Header } from 'semantic-ui-react'
import styled from 'styled-components'

import Layout from '../../components/Layout'
import Canvas from '../../components/canvas'

const SContainer = styled(Container)`
    height: 100%;
`

const RaimicaMap = (props) => (
    <Layout>
        <SContainer>
            <Header as="h1">The Raimica Region</Header>
            <Canvas markers={props.markers} />
        </SContainer>
    </Layout>
)

RaimicaMap.getInitialProps = async () => {
        const res = await fetch('http://localhost:4000/raimica-map/getMarkers')
        const markers = await res.json()
        return { markers }
    }

export default RaimicaMap
