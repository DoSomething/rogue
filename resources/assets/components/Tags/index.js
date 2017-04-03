import React from 'react';
import { map } from 'lodash';
import classnames from 'classnames';

class Tags extends React.Component {
  constructor() {
    super();

    // @TODO: This should come from the server!
    this.state = {
      'good_photo': true,
      'good_quote': false,
      'hidden': false,
      'sponsor': true,
      'storytelling': true,
    }

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(key) {
    this.setState({
      [key]: !this.state[key]
    });
  }

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
        <h4>Tags</h4>
        <ul className="aligned-actions">
          {map(tags, (label, key) => (
            <li key={key}>
              <button className={classnames('tag', {'is-active': this.state[key]})}
                      onClick={() => this.handleClick(key)}>{label}</button>
            </li>
          ))}
        </ul>
      </div>
    )
  }
}

export default Tags;
