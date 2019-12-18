import React, { useState } from 'react'
import Link from 'next/link'
import window from 'global'

import { Dropdown, Menu } from 'semantic-ui-react'


const Nav = (props) => {
    let path;
    if (window.location.pathname === '/') {
        path = 'home'
    }
    else {
        path = window.location.pathname
    }
    console.log(path)
    const [active, setActive] = useState(path);

    return (
        <Menu size='massive' as="nav">
            <Link href="/">
                <Menu.Item
                    name="home"
                    onClick={() => {
                        setActive('home')
                        props.setTitle('Home')
                    }}
                    active={active === 'home'}
                />
            </Link>
            <Dropdown item text="Maps">
                <Dropdown.Menu>
                    <Link href="/maps/raimica-map">
                        <Dropdown.Item
                            text="Raimica Map"
                            onClick={() => {
                                setActive('/maps/raimica-map')
                                props.setTitle('Raimica Map')
                            }}
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
