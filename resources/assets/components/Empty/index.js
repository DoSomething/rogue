import React from 'react';
import './empty.scss';

const Empty = (props) => (
  <div className="container empty">
    <div className="image" />
    <div className="text">
      <h2 className="heading -gamma">{props.details.header}</h2>
      <span className="copy">{props.details.copy}</span>
    </div>
  </div>
);


export default Empty;
