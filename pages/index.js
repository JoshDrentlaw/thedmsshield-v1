import React from 'react'
import { Container, Header } from 'semantic-ui-react'

import Layout from '../components/layout'

const Home = () => (
    <Layout>
        <Container>
            <Header as="h1">Home</Header>
            <h2>{process.env.DEV}</h2>
        </Container>
    </Layout>
)

export default Home
