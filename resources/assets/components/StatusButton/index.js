import React from 'react';
import './status-button.scss';
import classnames from 'classnames';

export default (props) => (
  <button className={classnames('button', '-outlined-button',  `-${props.type}`, {'is-selected' : props.status == props.type})} onClick={() => props.setStatus(props.type)}>
    {props.label}
  </button>
);
