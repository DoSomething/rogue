import React from 'react';

/**
 * Renders entity name and id.
 *
 * @param {String|Number} id
 * @param {String} name
 */
const EntityLabel = ({ id, name }) => (
  <>
    {name} <code className="footnote">({id})</code>
  </>
);

export default EntityLabel;
