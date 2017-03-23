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
          <p><strong>Photo Caption: </strong> {post['postable']['caption']}</p>
          <p><strong>Why Statement: </strong> {post['signup']['why_participated']}</p>
          <ul className="form-actions -inline">
            <li> <input className="button" value="Accepted"/></li>
            <li> <input className="button -secondary" value="Rejected"/></li>
          </ul>
        </div>
      </div>
    )
  }
}

export default InboxItem;
