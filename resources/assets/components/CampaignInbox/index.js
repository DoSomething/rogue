import React from 'react';
import { flatMap } from 'lodash';

import InboxItem from '../InboxItem';

class CampaignInbox extends React.Component {
  render() {
    const signups = this.props['Signups'];

    const posts = flatMap(signups, signup => {
      return signup.posts.map(post => {
        post.signup = signup;
        return post;
      });
    });

    return (
      <div>
          <ul>
            {
              Object.keys(posts)
                .map(key => <InboxItem key={key} details={posts[key]}/>)
            }
          </ul>
      </div>
    )
  }
}

export default CampaignInbox;
