import { ready } from './helpers';
import './app.scss';

const React = require('react');
const ReactDom = require('react-dom');

// @TEMP - remove when we start building out actual views.
const Greeter = (props) => {
  let message = `Hey ${props.message}`;
  let subtitle = `${props.subtitle}`;

  return <div className="container__block -narrow"><p>{ message }</p><h2>{subtitle}</h2></div>;
};

ready(() => {
  ReactDom.render(<Greeter {...window.STATE} />, document.getElementById('app'));
});
