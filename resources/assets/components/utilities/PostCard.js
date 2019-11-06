import React from 'react';

export const PostCard = ({ post }) => {
  return (
    <>
      {post.type == 'photo' ? (
        <img className="post-tile" alt="post image" src={url} />
      ) : (
        <div className="post-tile -fallback" />
      )}

      {post.type === 'photo' ? (
        <div className="admin-tools">
          <div className="admin-tools__links">
            <a href={`/originals/${post.id}`} target="_blank">
              Original Photo
            </a>
          </div>
      ) : null}
    </>
  );
};

export default PostCard;
