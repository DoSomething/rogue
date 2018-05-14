import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';
import classnames from 'classnames';

import './tags.scss';

class Tag extends React.Component {
  constructor() {
    super();

    this.state = {
      sending: false,
    };

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(post, label, isActive) {
    if (label === 'Hide In Gallery 👻') {
      label = 'Hide In Gallery';
    }

    this.setState({ sending: true });

    this.props.isClicked(post, label)
      .then(() => {
        this.setState({ sending: false });
      });
  }

  render() {
    return (<button disabled={this.props.disabled}
      className={classnames('tag', { 'is-active': this.props.isActive }, { 'is-loading': this.state.sending })}
      onClick={() => this.handleClick(this.props.post, this.props.label, this.props.isActive)}
    >{this.props.label}</button>);
  }
}

Tag.propTypes = {
  isClicked: PropTypes.func.isRequired,
  post: PropTypes.number.isRequired,
  isActive: PropTypes.bool.isRequired,
  label: PropTypes.string.isRequired,
};

class Tags extends React.Component {
  render() {
    const tags = {
      'good-submission': 'Good Submission',
      'good-quote': 'Good Quote',
      'hide-in-gallery': 'Hide In Gallery 👻',
      'good-for-sponsor': 'Good For Sponsor',
      'good-for-storytelling': 'Good For Storytelling',
      'irrelevant': 'Irrelevant',
      'inappropriate': 'Inappropriate',
      'unrealistic-quantity': 'Unrealistic Quantity',
      'test': 'Test',
      'incomplete-action': 'Incomplete Action',
    };

    const showTags = this.props.disabled ? 'disabled' : 'enabled';

    return (
      <div>
        <h4 className={showTags}>Tags</h4>
        <ul className="aligned-actions">
          {map(tags, (label, key) => (
            <li key={key}>
              <Tag isActive={this.props.tagged.includes(key)} isClicked={this.props.onTag} label={label} post={this.props.id} disabled={this.props.disabled} />
            </li>
          ))}
        </ul>
      </div>
    );
  }
}

Tags.propTypes = {
  tagged: PropTypes.array.isRequired, // eslint-disable-line react/forbid-prop-types
  onTag: PropTypes.func.isRequired,
  id: PropTypes.number.isRequired,
};

export default Tags;
