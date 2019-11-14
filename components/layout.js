import React from 'react'
import Head from 'next/head'

import Nav from '../components/nav'

const Layout = ({ children }) => (
    <>
        <Head>
            <title>Home</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css" />
            <link rel='icon' href='/favicon.ico' />
        </Head>

        <Nav />
        <main>
            {children}
        </main>
    </>
)

export default Layout