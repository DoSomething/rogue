import React from 'react';
import { map } from 'lodash';
import classnames from 'classnames';

import '../Tags/tags.scss';

class MultiValueFilter extends React.Component {
  constructor() {
    super();

    // this.state = {
    //   'good-photo': false,
    //   'good-quote': false,
    //   'hide-in-gallery': false,
    //   'good-for-sponsor': false,
    //   'good-for-storytelling': false,
    // }

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(key, activeFilter) {
    // this.setState({
    //   [key]: !this.state[key]
    // });

    let value = {
      [key]: !activeFilter,
    };

    this.props.updateFilters(value);
  }

  render() {
    return (
      <div className="container__block -third">
        <ul className="aligned-actions">
          {map(this.props.options, (option, key) => (
            <li key={key}>
              <button className={classnames('tag', {'is-active': option.active})}
                      onClick={() => this.handleClick(key, option.active)}>{option.label}</button>
            </li>
          ))}
        </ul>
      </div>
    )
  }
}

export default MultiValueFilter;
