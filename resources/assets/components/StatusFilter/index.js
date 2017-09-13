import React from 'react';

class TagsFilter extends React.Component {
  render() {
    return (
      <select onChange={(event) => this.props.setStatus(event.target.value)}>
        <option value="accepted">Accepted</option>
        <option value="pending">Pending</option>
        <option value="rejected">Rejected</option>
      </select>
    )
  }
}

export default TagsFilter;
