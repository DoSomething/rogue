import React from 'react';
import { map, findKey } from 'lodash';
import classnames from 'classnames';

class Tags extends React.Component {
  constructor() {
    super();

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(key) {
    // Ask the CampaignInbox to update the post's tags.
    this.props.onTag(this.props.id, key);


    // if (this.props.details.post.id.tagged.includes(key)) {
    //   this.props.onUpdate(this.props.details.post.id, { tagged: tagged.push(key) });
    // } else {
    //   var index = this.props.details.post.id.tagged.indexOf(key);
    //   this.props.onUpdate(this.props.details.post.id, { tagged: tagged.splice(index, 1)});
    // }
  }

  render() {
    const tags = {
      'good-photo': 'Good Photo',
      'good-quote': 'Good Quote',
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
              <button className={classnames('tag', {'is-active': findKey(this.props.tagged, {'tag_slug': key})})}
                      onClick={() => this.handleClick(key)}>{label}</button>
            </li>
          ))}
        </ul>
      </div>
    )
  }
}

export default Tags;
