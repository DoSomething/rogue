import React from 'react';
import './status-button.scss';
import classnames from 'classnames';

export default (props) => (
  <button className={classnames('button', `-${props.type}`)} onClick={() => props.setStatus(props.type)}>
    {props.label}
  </button>
);
