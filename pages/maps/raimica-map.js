import React from 'react'
import { Container, Header } from 'semantic-ui-react'
const ReactCanvas = require('react-canvas');

var Surface = ReactCanvas.Surface;
var Image = ReactCanvas.Image;

import Layout from '../../components/layout'

const RaimicaMap = () => (
    <Layout>
        <Container>
            <Header as="h1">The Raimica Region</Header>
        </Container>
    </Layout>
)

export default RaimicaMap
