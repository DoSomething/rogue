import React from 'react';

import TagsFilter from '../TagsFilter';

class PostFilter extends React.Component {
  constructor() {
    super();

    this.change = this.change.bind(this);
  }

  change(event) {
    this.props.onChange(event.target.value);
  }
  render() {
    return (
      <div className="container">
        <div className="container__block -third">
          <h4>Post Status</h4>
          <div className="select">
          {/* @TODO create a <Filter> component that takes in an array of value/labels and renders the select list and handles the change event.*/}
              <select onChange={(event) => this.change(event)}>
                  <option value="accepted">Accepted</option>
                  <option value="pending">Pending</option>
                  <option value="rejected">Rejected</option>
              </select>
          </div>
        </div>
        <div className="container__block -third">
          <h4>Tags</h4>
            <TagsFilter/>
        </div>
        <div className="container__block -third">
          <button className="button">Filter</button>
        </div>
      </div>
    )
  }
}

export default PostFilter;
