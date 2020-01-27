import React from 'react'
import { Header } from 'semantic-ui-react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'
import window from 'global'

import Layout from '../../components/layout'
import Canvas from '../../components/canvas'

const Container = styled.section`
    width: 100%;
    height: calc(100vh - 97.406px);
    margin: 0 auto;

    @media(min-width: 568px) {
        width: 100%;
        margin: 0;
    }
`

const RaimicaMap = (props) => {
    return (
        <Layout bg="black">
            <Container>
                <Canvas markers={props.markers} />
            </Container>
        </Layout>
    )
}

RaimicaMap.getInitialProps = async () => {
        const url =  process.env.URL
        const res = await fetch(url)
        const markers = await res.json()
        return { markers }
    }

export default RaimicaMap
