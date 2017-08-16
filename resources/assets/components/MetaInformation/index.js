import React from 'react';
import { map } from 'lodash';

const MetaInformation = (props) => (
  <div className="container">
    {props.title ? <h4 className="heading">{props.title}</h4> : null}
    {
      map(props.details, (item, key) => {
        return <span key={key}>{key}: {item}<br/></span>
      })
    }
  </div>
);


export default MetaInformation;
