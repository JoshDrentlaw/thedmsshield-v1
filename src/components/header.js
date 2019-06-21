import React, { useState } from "react"
import { Link } from "gatsby"

import styled from 'styled-components'

import Hamburger from '../assets/svg/hamburger.inline.svg'
import Instagram from '../assets/svg/instagram.inline.svg'
import Facebook from '../assets/svg/facebook.inline.svg'

const Wrapper = styled.div`
  @media (min-width: 1024px) {
    display: grid;
    grid-template-columns: 40% 60%;
    grid-template-areas: 'brand links';
  }
`

const Links = styled.div.attrs(({ open }) => ({
  visibility: open ? 'visible' : 'hidden',
  transform: open ? `translateX(0%)` : `translateX(100%)`,
}))`
  grid-area: links;
  position: relative;

  /* Medium devices (tablets, less than 992px) */
  @media (max-width: 1024px) {
    font-size: 1.5rem;
    width: 40vw; height: 100vh;
    padding: 1rem;
    padding-top: 4rem;
    position: absolute;
    visibility: ${props => props.visibility};
    top: 0; right: 0;
    transform: ${props => props.transform};
    transition: all 200ms ease-in;
  }
`

const hamburger = "flex items-center px-3 py-2 text-black bg-white rounded border border-black";

const HamburgerButton = (props) => {
  const toggle = () => {
    props.toggle(!props.state)
  }

  return (
    <div className="block ml-auto z-50 lg:hidden" style={{ gridArea: 'links' }}>
      <button className={hamburger} onClick={toggle}>
        <Hamburger className="fill-current h-3 w-3" />
      </button>
    </div>
  )
}

export const Socials = () => (
  <div className="pl-px2 w-1/2">
    <a href="#" className="text-white mr-4 inline">
      <Instagram className="fill-current w-4 h-4 inline" />
    </a>
    <a href="#" className="text-white inline">
      <Facebook className="fill-current w-4 h-4 inline" />
    </a>
  </div>
)

const wrapper = 'bg-transparent mx-auto p-4 flex justify-between items-center lg:w-1/2';

const links =
  `text-white lg:text-black flex flex-col justify-start items-start bg-transblack z-10
  lg:bg-transparent lg:flex-row lg:justify-between lg:items-center lg:visible`;

const Header = (props) => {
  const [open, setOpen] = useState(false);

  return(
    <nav className="fixed lg:relative w-full z-50">
      <Wrapper className={wrapper}>
        <span className="text-red-600 font-sans md:text-base text-lg whitespace-no-wrap hidden lg:inline" style={{ gridArea: 'brand' }}>Company Logo</span>
        <Links open={open} className={links}>
          <div className="">
            <Link className="block lg:inline lg:pr-2 lg:border-none border-b-2 border-white" activeClassName="active" to='/'>Home</Link>
            <Link className="block lg:inline lg:pl-2" activeClassName="active" to='/contact/'>Contact</Link>
          </div>
          <Socials />
        </Links>
        <HamburgerButton toggle={setOpen} state={open} />
      </Wrapper>
    </nav>
  )
}

export default Header
