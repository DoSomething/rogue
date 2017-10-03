import React from 'react';
import './empty.scss';

const Empty = (props) => (
  <div className="container empty">
    <div className="empty__image" />
    <div className="empty__text">
      <h2 className="heading -gamma">{props.header}</h2>
      <span className="copy">{props.copy ? props.copy : null }</span>
    </div>
  </div>
);


export default Empty;
