import React from 'react';

class InboxTile extends React.Component {
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
      <li>
        <img src={this.displayImage(post['url'])}/>
      </li>
    )
  }
}

export default InboxTile;
