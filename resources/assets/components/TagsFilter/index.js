import React from 'react';
import { map } from 'lodash';
import classnames from 'classnames';

import '../Tags/tags.scss';

class TagsFilter extends React.Component {
  constructor() {
    super();

    this.state = {
      'good-photo': false,
      'good-quote': false,
      'hide-in-gallery': false,
      'good-for-sponsor': false,
      'good-for-storytelling': false,
    }

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(key) {
    this.setState({
      [key]: !this.state[key]
    });

    this.props.onTag(key, !this.state[key]);
  }

  render() {
    const tags = {
      'good-photo': 'Good Photo',
      'good-quote': 'Good Quote',
      'hide-in-gallery': 'Hide In Gallery 👻',
      'good-for-sponsor': 'Good For Sponsor',
      'good-for-storytelling': 'Good For Storytelling',
    };

    return (
      <div>
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

export default TagsFilter;

