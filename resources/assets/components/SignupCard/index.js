import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';
import './signup-card.scss';
import PostTile from '../PostTile';
import { RestApiClient } from '@dosomething/gateway';
import { extractPostsFromSignups } from '../../helpers';

class SignupCard extends React.Component {
  /**
   * Gets the campaign run start date.
   *
   * @param {Object} campaignRuns
   * @param {String} campaignRunId
   * @return {String}
   */
  getCampaignRunStartDate(campaignRuns, campaignRunId) {
    for (let key in campaignRuns) {
      if (campaignRuns[key]['id'] == campaignRunId) {
        const date = campaignRuns[key]['start_date'];

        if (date) {
          return campaignRuns[key]['start_date'].split(" ")[0];
        }
      }
      return null;
    }
  }

  render() {
    const signup = this.props.signup;
    const campaign = this.props.campaign;
    const gallerySize = 4;

    const extraPostCount = signup.posts.data.length - gallerySize;

    const posts = signup.posts.data.slice(0, gallerySize).map((post, index) => {
      return <PostTile key={index} details={post} />;
    });

    const signupUrl = `/signups/${signup.signup_id}`;

    return (
        <article className="container__row signup-card">
          <a href={signupUrl}>
            <div className="container__block -half">
              <div className="container__row">
                <h2 className="heading">{campaign ? campaign.title : signup.campaign_id}</h2>
                <h4 className="heading">Campaign ID: {signup.campaign_id}</h4>
                <h4 className="heading">Campaign Run ID: {signup.campaign_run_id}</h4>
                {campaign ?
                  <h4 className="heading">Campaign Run Start Date: {this.getCampaignRunStartDate(campaign.campaign_runs.current, signup.campaign_run_id)}</h4>
                :null }
              </div>
              <div className="container__row">
                <h4 className="heading">Why Statement</h4>
                <p>{signup.why_participated}</p>
              </div>
            </div>
            <div className="container__block -half">
              { signup.quantity ?
                <div className="container__row figure -left -center">
                  <div className="figure__media">
                    <div className="quantity">{signup.quantity}</div>
                  </div>
                  <div className="figure__body">
                     <h4 className="reportback-noun-verb">{campaign ? `${campaign.reportback_info.noun} ${campaign.reportback_info.verb}` : '' }</h4>
                  </div>
                </div>
              : null }
              {posts.length ?
                <div className="container__row">
                  <h4>Items</h4>
                  <ul className="gallery">
                    {posts}
                    {extraPostCount > 0 ?
                      <li className="figure__media">
                        <div className="quantity">+{extraPostCount}</div>
                      </li>
                    : null}
                  </ul>
                </div>
              : null }
            </div>
          </a>
        </article>
    )
  }
}

SignupCard.propTypes = {
  signup: PropTypes.shape({
    posts: PropTypes.object,
    why_participated: PropTypes.string,
    signup_id: PropTypes.int,
    campaign_id: PropTypes.int,
    campaign_run_id: PropTypes.int,
    quantity: PropTypes.int,
  }).isRequired,
  campaign: PropTypes.shape({
    title: PropTypes.string,
    campaign_runs: PropTypes.object,
    reportback_info: PropTypes.object,
  }),
};

SignupCard.defaultProps = {
  campaign: null,
};


export default SignupCard;
