import React, { useState } from 'react'
import Link from 'next/link'

import { Dropdown, Menu } from 'semantic-ui-react'

const Nav = () => {
    const [active, setActive] = useState('home');

    return (
        <Menu size='massive'>
            <Menu.Item
                name="home"
                onClick={() => setActive('home')}
                active={active === 'home'}
            />
            <Menu.Menu position="left">
                <Dropdown item text="Maps">
                    <Dropdown.Menu>
                        <Dropdown.Item>
                            <Menu.Item
                                name="raimica-map"
                                onClick={() => setActive('raimica-map')}
                                active={active === 'raimica-map'}
                            />
                        </Dropdown.Item>
                    </Dropdown.Menu>
                </Dropdown>
            </Menu.Menu>
        </Menu>
    )
}

export default Nav
