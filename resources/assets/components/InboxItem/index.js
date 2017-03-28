import React from 'react';
import { map } from 'lodash';

class InboxItem extends React.Component {
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
          <p><strong>Quantity: </strong> {post['signup']['quantity']}</p>
          <h4>Photo Caption</h4>
          <p>{post['postable']['caption']}</p>
          <h4>Why Statement</h4>
          <p>{post['signup']['why_participated']}</p>
          <ul className="form-actions -inline">
            <li> <input className="button" value="Accepted"/></li>
            <li> <input className="button -secondary" value="Rejected"/></li>
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
