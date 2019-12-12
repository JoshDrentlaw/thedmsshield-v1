import App from 'next/app'
import React from 'react'
import { ThemeProvider } from 'styled-components'
/* import PNotify from 'pnotify/dist/es/PNotify'
import PNotifyButtons from 'pnotify/dist/es/PNotifyButtons'

export {PNotify, PNotifyButtons} */

const theme = {
    colors: {
        primary: '#0070f3',
    },
}

export default class MyApp extends App {
    render() {
        const { Component, pageProps } = this.props
        return (
            <ThemeProvider theme={theme}>
                <Component {...pageProps} />
            </ThemeProvider>
        )
    }
}