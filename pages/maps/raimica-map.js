import React from 'react'
import { Header } from 'semantic-ui-react'
import styled from 'styled-components'
import fetch from 'isomorphic-unfetch'

import Layout from '../../components/layout'
import Canvas from '../../components/canvas'

const Container = styled.section`
    height: 100%;
    width: 90%;
    margin: 0 auto;

    @media(min-width: 568px) {
        width: 100%;
        margin: 0;
    }
`

const RaimicaMap = (props) => {
    return (
        <Layout>
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
