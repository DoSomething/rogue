import React from 'react';
import { map } from 'lodash';
import classnames from 'classnames';

import '../Tags/tags.scss';

class MultiValueFilter extends React.Component {
  constructor() {
    super();

    this.state = {};

    this.handleClick = this.handleClick.bind(this);
  }

  componentDidMount() {
    const options = this.props.options;
    this.setState({ ...options });
  }

  componentDidUpdate() {
    this.props.updateFilters(this.state);
  }

  shouldComponentUpdate(nextProps, nextState) {
    return nextState !== this.state;
  }

  handleClick(key, activeFilter) {
    this.setState({
      [key]: {
        active: !activeFilter,
        label: this.state[key].label
      }
    });
  }

  render() {
    return (
      <div className="container__block -third">
        <ul className="aligned-actions">
          {map(this.props.options, (option, key) => (
            <li key={key}>
              {this.state[key] ?
                <button className={classnames('tag', {'is-active':  this.state[key].active})}
                      onClick={() => this.handleClick(key, this.state[key].active)}>{option.label}</button>
              : null}
            </li>
          ))}
        </ul>
      </div>
    )
  }
}

export default MultiValueFilter;
