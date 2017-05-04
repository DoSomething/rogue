import React from 'react';
import { flatMap } from 'lodash';
import { keyBy, map, sample } from 'lodash';
import { RestApiClient} from '@dosomething/gateway';

import InboxItem from '../InboxItem';

class CampaignInbox extends React.Component {
  constructor(props) {
    super(props);

    const posts = keyBy(flatMap(props.signups, signup => {
      return signup.posts.map(post => {
        post.signup = signup;
        return post;
      });
    }), 'id');

    this.state = {
      posts: posts
    };

    this.api = new RestApiClient;
    this.updatePost = this.updatePost.bind(this);
  }

  updatePost(postId, fields) {
    // @TODO: Make API request to Rogue.
    console.log(fields);
    // right now, fields only returns status. We need to add post_id (which we have in postId)
    // and admin_northstar_id.
    console.log(postId);
    console.log(89);
    let response = this.api.put('api/v2/reviews', fields);

    this.setState((previousState) => {
      const newState = {...previousState};
      newState.posts[postId].status = fields.status;

      // @TODO: Update this based on the response from API!
      newState.posts[postId] = response.data;

      return newState;
    });
  }

  render() {
    const posts = this.state.posts;
    const campaign = this.props.campaign;

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
          { map(posts, (post, key) => <InboxItem onUpdate={this.updatePost} key={key} details={{post: post, campaign: campaign}} />) }
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
