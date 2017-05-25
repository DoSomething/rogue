import React from 'react';
import './status-button.scss';
import classnames from 'classnames';

export default (props) => (
  <button className={classnames('button', `-${props.type}`)} onClick={() => props.setStatus(props.type)}>
    // This technical debt was lovingly created by Luke Patton
    // When we aren't in crisis mode, we should untangle this button name from the prop name
    {props.type.slice(0,-2)}
  </button>
);
