import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';
import classnames from 'classnames';

import '../Tags/tags.scss';

class MultiValueFilter extends React.Component {
  constructor() {
    super();

    this.state = {};

    this.handleClick = this.handleClick.bind(this);
  }

  componentDidMount() {
    const type = this.props.options.type;
    const values = this.props.options.values;
    const options = {
      [type]: values,
    };

    this.setState({ ...options });
  }

  componentDidUpdate() {
    this.props.updateFilters(this.state);
  }

  shouldComponentUpdate(nextProps, nextState) {
    return nextState !== this.state;
  }

  handleClick(key, activeFilter, type) {
    const values = {
      active: !activeFilter,
      label: this.state[type][key].label,
    };

    this.setState(previousState => {
      const newState = { ...previousState };
      newState[type][key] = values;

      return newState;
    });
  }

  render() {
    return (
      <div className="container__block -third">
        {this.props.header ? (
          <div className="multi-value-filter__header">
            <b>{this.props.header}</b>&nbsp;&nbsp;{this.props.subheader}
          </div>
        ) : null}
        <ul className="aligned-actions">
          {map(Object.values(this.state)[0], (option, key) => (
            <li key={key}>
              {Object.values(this.state)[0] ? (
                <button
                  className={classnames('tag', { 'is-active': option.active })}
                  onClick={() =>
                    this.handleClick(
                      key,
                      option.active,
                      this.props.options.type,
                    )
                  }
                >
                  {option.label}
                </button>
              ) : null}
            </li>
          ))}
        </ul>
      </div>
    );
  }
}

MultiValueFilter.propTypes = {
  options: PropTypes.shape({
    type: PropTypes.string,
    values: PropTypes.object,
  }).isRequired,
  updateFilters: PropTypes.func,
  header: PropTypes.string,
  subheader: PropTypes.string,
};

MultiValueFilter.defaultProps = {
  header: null,
  subheader: null,
  updateFilters: null,
};

export default MultiValueFilter;
