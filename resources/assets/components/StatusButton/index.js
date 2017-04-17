import React from 'react';
import './status-button.scss';
// import { map } from 'lodash';


class StatusButton extends React.Component {
  getClass() {
    return `button -${this.props.type}`;
  }

  render() {
    return (
      <button className={this.getClass()} onClick="">{this.props.type}</button>
    )
  }
}

export default StatusButton;
