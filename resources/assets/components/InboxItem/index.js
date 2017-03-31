import React from 'react';
import { map } from 'lodash';
import { calculateAge } from '../../helpers';

import Tags from '../Tags';

class InboxItem extends React.Component {
  constructor () {
    super();
    this.state = {
      showTags: false,
    };
    this._onAcceptClick = this._onAcceptClick.bind(this);
  }

  _onAcceptClick() {
    this.setState({
      showTags: true,
    });
  }

  displayImage(photo_url) {
    if (photo_url == "default") {
      return "https://pics.onsizzle.com/bork-2411135.png";
    }
    else {
      return photo_url;
    }
  }

  render() {
    const post = this.props.details;

    return (
      <div className="container__row">
        <div className="container__block -half">
          <img src={this.displayImage(post['postable']['file_url'])}/>
        </div>
        <div className="container__block -half">
          <h2>{post['user']['first_name']} {post['user']['last_name']}, {calculateAge(post['user']['birthdate'])}</h2>
          <p><em>{post['user']['email']}</em></p>
          <p><em>{post['user']['mobile']}</em></p>
          <p><strong>Quantity: </strong> {post['signup']['quantity']}</p>
          <h4>Photo Caption</h4>
          <p>{post['postable']['caption']}</p>
          <h4>Why Statement</h4>
          <p>{post['signup']['why_participated']}</p>
          <ul className="form-actions -inline">
            <li> <input className="button" value="Accepted" onClick={this._onAcceptClick}/></li>
            <li> <input className="button -secondary" value="Rejected"/></li>
            {this.state.showTags? <Tags /> : null}
            <br/>
            <li> <input className="button -tertiary" value="Delete"/></li>
          </ul>
          <h4>Meta</h4>
          <p>
            Post ID: {post['postable_id']} <br/>
            Source: {post['source']} <br/>
            Submitted: {post['created_at']} <br/>
          </p>
        </div>
      </div>
    )
  }
}

export default InboxItem;
