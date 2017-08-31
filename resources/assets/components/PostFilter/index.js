import React from 'react';

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
          <h4>Post Filter</h4>
          <div className="select">
          {/* @TODO create a <Filter> component that takes in an array of value/labels and renders the select list and handles the change event.*/}
              <select onChange={(event) => this.change(event)}>
                  <option value="accepted">Accepted</option>
                  <option value="pending">Pending</option>
                  <option value="rejected">Rejected</option>
                  <option value="good-photo">Good Photo</option>
                  <option value="good-quote">Good Quote</option>
                  <option value="hide-in-gallery">Hide In Gallery 👻</option>
                  <option value="good-for-sponsor">Good For Sponsor</option>
                  <option value="good-for-storytelling">Good For Storytelling</option>
              </select>
          </div>
        </div>
      </div>
    )
  }
}

export default PostFilter;
