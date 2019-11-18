import React from 'react'
import Head from 'next/head'
import styled from 'styled-components'

import Nav from './nav'

const Main = styled.main`
    height: calc(100vh - 66px);
`

const Layout = ({ children }) => (
    <>
        <Head>
            <title>Home</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css" />
            <link rel='icon' href='/favicon.ico' />
        </Head>

        <Nav />
        <Main>
            {children}
        </Main>
    </>
)

export default Layout