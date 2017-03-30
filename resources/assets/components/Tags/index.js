import React from 'react';
import { map } from 'lodash';

class Tags extends React.Component {
  render() {
    // const signups = this.props['signups'];

    // const posts = flatMap(signups, signup => {
    //   return signup.posts.map(post => {
    //     post.signup = signup;
    //     return post;
    //   });
    // });

    return (
        <ul className="form-actions -inline">
          <li><b> Tags </b></li>
          <br/>
          <li> <input className="button -secondary" value="Good Photo"/></li>
          <li> <input className="button -secondary" value="Good Quote"/></li>
          <br/>
          <li> <input className="button -secondary" value="Hide in Gallery"/></li>
          <li> <input className="button -secondary" value="Good for Sponsor"/></li>
        </ul>
    )
  }
}

export default Tags;
