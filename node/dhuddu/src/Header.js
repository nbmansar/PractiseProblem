import React, { useState } from 'react';

function Header() {
    const propCss = { backgroundColor: 'green', color: 'white' };
    const [count, setCount] = useState("SAM");

    const handleClick = () => {

        const name = ['Ansar','Mass','Sam'];
        const int = Math.floor(Math.random()*3);
        setCount(name[int]);
        
    };

    return (
        <div className='Ansar' style={propCss}>
            <h5>
                There was an idea. Count: {count}
                <button onClick={() => handleClick()}>grab here</button>
            </h5>
        </div>
    );
}

export default Header;
