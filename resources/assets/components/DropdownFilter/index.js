import React from 'react';
import { map } from 'lodash';

class DropdownFilter extends React.Component {
  constructor() {
    super();

    this.change = this.change.bind(this);
  }

  componentDidMount() {
    const type = this.props.options.type;
    const defaultValue = this.props.options.default;

    this.props.updateFilters({ [type]: defaultValue });
  }

  change(event) {
    const type = this.props.options.type;

    this.props.updateFilters({ [type]: event.target.value });
  }

  render() {
    return (
        <div className="container__block -third">
          <h2 className="heading -delta">{this.props.header}</h2>

          <div className="select">
            <select onChange={(event) => this.change(event)}>
              {map(this.props.options.values, (option, key) => (
                 <option value={key} key={key}>{option}</option>
              ))}
            </select>
          </div>
        </div>
    )
  }
}

export default DropdownFilter;
