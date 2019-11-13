import React from 'react';
import { map } from 'lodash';
import gql from 'graphql-tag';
import usePortal from 'react-useportal';
import { Link } from 'react-router-dom';
import { parse, format } from 'date-fns';

import Quantity from './Quantity';
import PostTile from './PostTile';
import { TAGS } from '../helpers';
import TextBlock from './TextBlock';
import Modal from './utilities/Modal';
import PostCard from './utilities/PostCard';
import DeleteButton from './utilities/DeleteButton';
import TagButton, { TagButtonFragment } from './utilities/TagButton';
import QuantityForm, { QuantityFormFragment } from './utilities/QuantityForm';
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
    ...QuantityForm
    quantity
    text
    type
    url
    createdAt
    source
    location(format: HUMAN_FORMAT)
    deleted

    actionDetails {
      name
      noun
      verb
    }

    campaign {
      id
      internalTitle
    }

    signupId
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
  ${QuantityFormFragment}
  ${UserInformationFragment}
`;

const ReviewablePost = ({ post }) => {
  var { openPortal, closePortal, isOpen, Portal } = usePortal({
    bindTo: document.getElementById('modal-portal'),
  });

  // If we have a 'deleted' flag, it means that we've deleted this
  // post since first viewing it & cannot make any further changes.
  if (post.deleted) {
    return (
      <div className="border-solid border-gray-300 border-b-2 py-4 my-4">
        <p className="text-sm text-gray text-center italic">
          This post (#{post.id}) has been deleted.
        </p>
      </div>
    );
  }

  return (
    <div className="border-solid border-gray-300 border-b-2 py-4 my-4 clearfix">
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
        <UserInformation user={post.user} linkSignup={post.signupId}>
          {post.quantity ? (
            <Quantity
              quantity={post.quantity}
              noun={post.actionDetails.noun}
              verb={post.actionDetails.verb}
            />
          ) : null}

          <div className="container -padded">
            <button className="button -tertiary" onClick={openPortal}>
              Edit Quantity
            </button>

            {isOpen ? (
              <Portal>
                <Modal onClose={closePortal}>
                  <QuantityForm post={post} />
                </Modal>
              </Portal>
            ) : null}
          </div>

          <div className="container -padded">
            <TextBlock
              title={post.type === 'photo' ? 'Caption' : 'Text'}
              content={post.text}
            />
          </div>

          <div className="container">
            <TextBlock
              title="Why Statement"
              content={post.signup ? post.signup.whyParticipated : 'N/A'}
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
        <div className="container__row">
          <ul className="form-actions -inline">
            <li>
              <DeleteButton post={post} />
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
            {map(TAGS, (label, id) => (
              <TagButton key={id} tag={id} label={label} post={post} />
            ))}
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
              ID: <a href={`/signups/${post.signupId}`}>{post.signupId}</a>,
              User: <Link to={`/users/${post.user.id}`}>{post.user.id}</Link>,
              Source: post.signup ? post.signup.source : '–',
            }}
          />
        </div>
      </div>
    </div>
  );
};

export default ReviewablePost;
