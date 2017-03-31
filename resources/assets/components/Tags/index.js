import React from 'react';
import { map } from 'lodash';

class Tags extends React.Component {
  render() {
    const tags = {
      'good_photo': 'Good Photo',
      'good_quote': 'Good Quote',
      'hidden': 'Hide in Gallery',
      'sponsor': 'Good for Sponsor',
    };

    return (
      <div>
        <div className="container__block -half">
          <h4>Tags</h4>
        </div>
        <br/>
        <ul className="form-actions -inline">
          {map(tags, (label, value) => <input className="button -secondary" value={label}/>)}
        </ul>
      </div>
    )
  }
}

export default Tags;
