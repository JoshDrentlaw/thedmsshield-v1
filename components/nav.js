import React, { useState } from 'react'
import Link from 'next/link'

import { Dropdown, Menu } from 'semantic-ui-react'

const Nav = () => {
    const [active, setActive] = useState('home');

    return (
        <Menu size='massive' as="nav">
            <Link href="/">
                <Menu.Item
                    name="home"
                    onClick={() => setActive('home')}
                    active={active === 'home'}
                />
            </Link>
            <Link href="/maps/raimica-map">
                <Menu.Item
                    name="raimica-map"
                    onClick={() => setActive('raimica-map')}
                    active={active === 'raimica-map'}
                />
            </Link>
        </Menu>
    )
}

export default Nav
