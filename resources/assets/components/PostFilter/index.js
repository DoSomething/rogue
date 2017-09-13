import React from 'react';

import TagsFilter from '../TagsFilter';

class PostFilter extends React.Component {
  constructor() {
    super();

    this.state = {
      'status': 'accepted',
      'tags': {
        'good-photo': false,
        'good-quote': false,
        'hide-in-gallery': false,
        'good-for-sponsor': false,
        'good-for-storytelling': false,
      }
    }

    this.setStatus = this.setStatus.bind(this);
    this.setTags = this.setTags.bind(this);
  }

  setStatus(event){
    this.setState((previousState) => {
      const newState = {...previousState};
      newState.status = event;

      return newState;
    });
  }

  setTags(tag, bool){
    this.setState((previousState) => {
      const newState = {...previousState};
      newState.tags[tag] = bool;

      return newState;
    });
  }

  render() {
    return (
      <div className="container">
        <div className="container__block -third">
          <h4>Post Status</h4>
          <div className="select">
          {/* @TODO create a <Filter> component that takes in an array of value/labels and renders the select list and handles the change event.*/}
              <select onChange={(event) => this.setStatus(event)}>
                  <option value="accepted">Accepted</option>
                  <option value="pending">Pending</option>
                  <option value="rejected">Rejected</option>
              </select>
          </div>
        </div>
        <div className="container__block -third">
          <h4>Tags</h4>
            <TagsFilter onTag={this.setTags}/>
        </div>
        <div className="container__block -third">
          <button className="button" onSubmit={() => props.setStatus(props.type)}>Filter</button>
        </div>
      </div>
    )
  }
}

export default PostFilter;
