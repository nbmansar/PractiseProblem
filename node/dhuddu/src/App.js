import logo from './logo.svg';
import './App.css';
import Header from './Header';
//import styled from 'styled-components';

function Ansar(){
  return "ansar";
}

function App() {
  return (
    <div className="App Ansars">
        <h5>{Ansar()}</h5>
        <p><Header/></p>
    </div>
  );
}

export default App;
