import React from 'react'
import { Container, Header } from 'semantic-ui-react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'

import Layout from '../../components/layout'
import Canvas from '../../components/canvas'

const SContainer = styled(Container)`
    height: 100%;
`

const RaimicaMap = (props) => {
    return (
        <Layout>
            <SContainer>
                <Header as="h1">The Raimica Region</Header>
                <Canvas markers={props.markers} />
            </SContainer>
        </Layout>
    )
}

RaimicaMap.getInitialProps = async () => {
        const url =  (process.env.NODE_ENV === 'production' ? 'https://thedmsshield.com/api/getMarkers' : `http://localhost:3000/api/markers`)
        const res = await fetch(`http://localhost:3000/api/markers`)
        const markers = await res.json()
        return { markers }
    }

export default RaimicaMap
