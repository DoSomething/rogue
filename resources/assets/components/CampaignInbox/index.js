import React from 'react';
import { flatMap } from 'lodash';
import { map, sample } from 'lodash';

import InboxItem from '../InboxItem';

class CampaignInbox extends React.Component {
  render() {
    const signups = this.props['signups'];

    const posts = flatMap(signups, signup => {
      return signup.posts.map(post => {
        post.signup = signup;
        return post;
      });
    });

    const nothingHere = [
      'https://media.giphy.com/media/3og0IT9dAZyMz3lXNe/giphy.gif',
      'https://media.giphy.com/media/Lny6Rw04nsOOc/giphy.gif',
      'https://media.giphy.com/media/YdhvjTeL83pNS/giphy.gif',
      'https://media.giphy.com/media/26ufnwz3wDUli7GU0/giphy.gif',
      'https://media.giphy.com/media/lYHbL5QY52Kcw/giphy.gif',
    ];

    if (posts.length !== 0) {
      return (
        <div className="container">
          { map(posts, (post, key) => <InboxItem key={key} details={post} />) }
        </div>
      )
    } else {
      // @todo - make this into an actual component.
      return (
        <div className="container">
          <h2 className="-padded">No Posts to review!</h2>
          <div className="container">
            <img src={sample(nothingHere)} />
          </div>
        </div>
      )
    }

  }
}

export default CampaignInbox;
