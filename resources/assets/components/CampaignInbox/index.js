import React from 'react';
import { flatMap } from 'lodash';
import { map } from 'lodash';

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

    return (
      <div className="container">
        { map(posts, (post, key) => <InboxItem key={key} details={post} />) }
      </div>
    )
  }
}

export default CampaignInbox;
