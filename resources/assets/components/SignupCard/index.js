import React from 'react';

class SignupCard extends React.Component {
  render() {
    const signup = this.props.signup;

    return (
      <div className="container__block">
        <h2>{signup.campaign_id}</h2>
      </div>
    )
  }
}

export default SignupCard;
