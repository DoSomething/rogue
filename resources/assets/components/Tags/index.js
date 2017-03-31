import React from 'react';
import { map } from 'lodash';

class Tags extends React.Component {
  render() {
    const tags = {
      'good_photo': 'Good Photo',
      'good_quote': 'Good Quote',
      'hidden': 'Hide in Gallery',
      'sponsor': 'Good for Sponsor',
      'storytelling': 'Good for Storytelling',
    };

    return (
      <div>
        <br/>
        <h4>Tags</h4>
        <ul className="form-actions -inline">
          <li>{map(tags, (label, value) => <input className="tag" type="button" value={label}/>)}</li>
        </ul>
      </div>
    )
  }
}

export default Tags;
