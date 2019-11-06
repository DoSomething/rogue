import React from 'react';
import gql from 'graphql-tag';
import { without } from 'lodash';
import classNames from 'classnames';
import { useMutation } from '@apollo/react-hooks';

export const TagButtonFragment = gql`
  fragment TagButton on Post {
    id
    tags
  }
`;

const TAG_POST_MUTATION = gql`
  mutation TagPostMutation($id: Int!, $tag: String!) {
    tagPost(id: $id, tag: $tag) {
      ...TagButton
    }

    ${TagButtonFragment}
  }
`;

export const TagButton = ({ post, tag, label }) => {
  const hasTag = post.tags.includes(tag);

  const [tagPost] = useMutation(TAG_POST_MUTATION, {
    variables: {
      id: post.id,
      tag: tag,
    },
    // We'll optimistically update the interface with the given tag
    // before waiting for the full network round-trip. Snappy!
    optimisticResponse: {
      __typename: 'Mutation',
      tagPost: {
        __typename: 'Post',
        id: post.id,
        tags: hasTag ? without(post.tags, tag) : [...post.tags, tag],
      },
    },
  });

  return (
    <button
      className={classNames('tag', { 'is-active': hasTag })}
      onClick={tagPost}
    >
      {label}
    </button>
  );
};

export const AllTagButtons = ({ post }) => (
  <>
    <TagButton post={post} tag="good-submission" label="Good Submission" />
    <TagButton post={post} tag="good-quote" label="Good Quote" />
    <TagButton post={post} tag="good-for-brand" label="Good For Brand" />
    <TagButton post={post} tag="good-for-sponsor" label="Good For Sponsor" />
    <TagButton post={post} tag="group-photo" label="Group Photo" />
    <TagButton post={post} tag="hide-in-gallery" label="Hide In Gallery 👻" />
    <TagButton post={post} tag="irrelevant" label="Irrelevant" />
    <TagButton post={post} tag="inappropriate" label="Inappropriate" />
    <TagButton
      post={post}
      tag="unrealistic-quantity"
      label="Unrealistic Quantity"
    />
    <TagButton post={post} tag="test" label="Test" />
    <TagButton post={post} tag="incomplete-action" label="Incomplete Action" />
    <TagButton post={post} tag="bulk" label="Bulk" />
  </>
);

export default TagButton;
