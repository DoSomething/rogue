import React from 'react';

const TextBlock = (props) => (
  <div className="container">
    <h4 className="heading">{props.title}</h4>
    <p>{props.content}</p>
  </div>
);


export default TextBlock;
