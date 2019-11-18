import React from 'react'
import { Container, Header } from 'semantic-ui-react'
import styled from 'styled-components'

import Layout from '../../components/Layout'
import Canvas from '../../components/canvas'

const SContainer = styled(Container)`
    height: 100%;
`

const RaimicaMap = () => (
    <Layout>
        <SContainer>
            <Header as="h1">The Raimica Region</Header>
            <Canvas />
        </SContainer>
    </Layout>
)

export default RaimicaMap
