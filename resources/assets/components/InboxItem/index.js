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
      <div>
          <li> 
            <p>ID: {post['postable']['id']}</p>
            <p><img src={this.displayImage(post['postable']['file_url'])}/></p>
            <p>Caption: {post['postable']['caption']}</p>
            <p>Why Participated: {post['signup']['why_participated']}</p>
          </li>
      </div>
    )
  }
}

export default InboxItem;
