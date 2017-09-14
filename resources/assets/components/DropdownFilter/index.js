import React from 'react';
import { map } from 'lodash';

class DropdownFilter extends React.Component {
  constructor() {
    super();

    this.change = this.change.bind(this);
  }

  change(event) {
    this.props.updateFilters(event.target.value);
  }

  render() {
    return (
        <div className="container__block -third">
          <div className="select">
            <select onChange={(event) => this.change(event)}>
              {map(this.props.options, (option, key) => (
                 <option value={key} key={key}>{option}</option>
              ))}
            </select>
          </div>
        </div>
    )
  }
}

export default DropdownFilter;
