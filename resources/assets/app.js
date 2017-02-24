// import '@dosomething/forge';

import { ready } from './helpers';
import './app.scss';

const React = require('react');
const ReactDom = require('react-dom');

const Greeter = (props) => {
  let message = `Hey ${props.name}`;

  return <h1>{ message }</h1>;
};

ready(() => {
  ReactDom.render(<Greeter {...window.STATE} />, document.getElementById('app'));
});
