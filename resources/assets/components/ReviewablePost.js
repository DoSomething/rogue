import React from 'react';
import gql from 'graphql-tag';
import classNames from 'classnames';
import { Link } from 'react-router-dom';
import { parse, format } from 'date-fns';

import Quantity from './Quantity';
import PostTile from './PostTile';
import TextBlock from './TextBlock';
import PostCard from './utilities/PostCard';
import { AllTagButtons, TagButtonFragment } from './utilities/TagButton';
import MetaInformation from './MetaInformation';
import {
  AcceptButton,
  RejectButton,
  ReviewButtonFragment,
} from './utilities/ReviewButton';
import UserInformation, {
  UserInformationFragment,
} from './Users/UserInformation';

export const ReviewablePostFragment = gql`
  fragment ReviewablePost on Post {
    ...ReviewButton
    ...TagButton
    quantity
    text
    type
    url
    createdAt
    source
    location(format: HUMAN_FORMAT)

    actionDetails {
      name
      noun
      verb
    }

    campaign {
      id
      internalTitle
    }

    signup {
      id
      whyParticipated
      source
    }

    user {
      id
      ...UserInformation
    }
  }

  ${ReviewButtonFragment}
  ${TagButtonFragment}
  ${UserInformationFragment}
`;

const ReviewablePost = ({ post }) => {
  return (
    <div className="post container__row">
      <div className="container__block -third">
        <PostCard post={post} />
        <ul className="gallery -duo">
          {[
            /* todo */
          ].map(siblingPost => (
            <li key={siblingPost.id}>
              <PostTile post={siblingPost} />
            </li>
          ))}
        </ul>
      </div>

      <div className="container__block -third">
        <UserInformation user={post.user} linkSignup={post.signup.id}>
          {post.quantity ? (
            <Quantity
              quantity={post.quantity}
              noun={post.actionDetails.noun}
              verb={post.actionDetails.verb}
            />
          ) : null}

          {/* TODO: Need to re-implement the 'edit quantity' modal. */}

          <div className="container -padded">
            <TextBlock
              title={post.type === 'photo' ? 'Caption' : 'Text'}
              content={post.text}
            />
          </div>

          <div className="container">
            <TextBlock
              title="Why Statement"
              content={post.signup.whyParticipated}
            />
          </div>
        </UserInformation>
      </div>

      <div className="container__block -third">
        <div className="container__row">
          <ul className="form-actions -inline">
            <li>
              <AcceptButton post={post} />
            </li>
            <li>
              <RejectButton post={post} />
            </li>
          </ul>
        </div>
        {post.status !== 'PENDING' ? (
          <div className="container__row">
            <h4>
              Tags
              <a className="footnote" href="/faq#tags" target="_blank">
                (see definitions)
              </a>
            </h4>
            <AllTagButtons post={post} />
          </div>
        ) : null}
        <div className="container__row">
          <MetaInformation
            title="Post Information"
            details={{
              ID: post.id,
              Campaign: (
                <a href={`/campaign-ids/${post.campaign.id}`}>
                  {post.campaign.internalTitle}
                </a>
              ),
              Action: (
                <a href={`/campaign-ids/${post.campaign.id}#actions`}></a>
              ),
              Type: post.type,
              Source: post.source,
              Location: post.location,
              Submitted: format(parse(post.createdAt), 'M/D/YYYY h:m:s'),
            }}
          />
        </div>
        <div className="container__row">
          <MetaInformation
            title="Signup Information"
            details={{
              ID: <a href={`/signups/${post.signup.id}`}>{post.signup.id}</a>,
              User: <Link to={`/users/${post.user.id}`}>{post.user.id}</Link>,
              Source: post.signup.source,
            }}
          />
        </div>
      </div>
    </div>
  );
};

export default ReviewablePost;
