import React, { useState } from 'react'
import Link from 'next/link'

import { Dropdown, Menu } from 'semantic-ui-react'


const Nav = () => {
    let path;
    if (typeof window !== 'undefined') {
        if (window.location.pathname === '/') {
            path = 'home'
        }
        else {
            path = window.location.pathname
        }
    }
    const [active, setActive] = useState();

    return (
        <Menu size='massive' as="nav">
            <Link href="/">
                <Menu.Item
                    name="home"
                    onClick={() => setActive('home')}
                    active={active === 'home'}
                />
            </Link>
            <Dropdown item text="Maps">
                <Dropdown.Menu>
                    <Link href="/maps/raimica-map">
                        <Dropdown.Item
                            text="Raimica Map"
                            onClick={() => setActive('/maps/raimica-map')}
                            active={active === '/maps/raimica-map'}
                        >
                        </Dropdown.Item>
                    </Link>
                </Dropdown.Menu>
            </Dropdown>
        </Menu>
    )
}

export default Nav
