import React, { useState } from 'react'
import Head from 'next/head'
import styled from 'styled-components'

import Nav from './nav'

const Main = styled.main`
    height: calc(100vh - 53px);
    background-color: ${props => props.bg ? props.bg : 'white'};
`

class Layout extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            title: ''
        }
    }

    setTitle = (title) => {
        this.setState({ title })
    }

    render () {
        return (
            <>
                <Head>
                    <title>{this.state.title}</title>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css" />
                    <link rel='icon' href='/favicon.ico' />
                </Head>

                <Nav setTitle={this.setTitle} title={this.state.title} />
                <Main bg={this.props.bg}>
                    {this.props.children}
                </Main>
            </>
        )
    }
}

export default Layout