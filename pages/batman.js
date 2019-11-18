import Layout from '../components/Layout';
import fetch from 'isomorphic-unfetch'

const Batman = props => (
    <Layout>
        <h1>Batman TV Shows</h1>
        <ul>
            
        </ul>
    </Layout>
);

Batman.getInitialProps = async function () {
    console.log('getting props')
    const res = await fetch('http://localhost:3000/pages/api/getMarkers.js');
    console.log({res})
    const data = await res.json();

    console.log(`Show data fetched. ${data}`);

    return {
        data
    };
};

export default Batman;