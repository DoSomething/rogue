import React from 'react';
import { map } from 'lodash';

class DropdownFilter extends React.Component {
  constructor() {
    super();

    this.change = this.change.bind(this);
  }

  componentDidMount() {
    // {
    //   this.props.type: this.props.default
    // }
    this.props.updateFilters({
      this.props.type: this.props.default
    });
  }

  change(event) {
    // this.props.updateFilters(event.target.value);
    this.props.updateFilters({
      this.props.type: event.targe.value
    });
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
