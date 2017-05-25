import React from 'react';
import { map, findKey } from 'lodash';
import classnames from 'classnames';

class Tags extends React.Component {
  constructor() {
    super();

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(label) {
    // Ask the CampaignInbox to update the post's tags.
    this.props.onTag(this.props.id, label);
  }

  render() {
    const tags = {
      'good-photo': 'Good Photo',
      'good-quote': 'Good Quote',
      'hide-in-gallery': 'Hide In Gallery',
      'good-for-sponsor': 'Good For Sponsor',
      'good-for-storytelling': 'Good For Storytelling',
    };

    return (
      <div>
        <h4>Tags</h4>
        <ul className="aligned-actions">
          {map(tags, (label, key) => (
            <li key={key}>
              <button className={classnames('tag', {'is-active': findKey(this.props.tagged, {'tag_slug': key})})}
                      onClick={() => this.handleClick(label)}>{label}</button>
            </li>
          ))}
        </ul>
      </div>
    )
  }
}

export default Tags;
