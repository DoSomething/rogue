import React from 'react';
import './status-button.scss';
// import { map } from 'lodash';


class StatusButton extends React.Component {
  getClass() {
    return `button -${this.props.type}`;
  }
  // Ideal setStatus would be defined on this component and would set some sort of global state via redux
  render() {
    return (
      <button className={this.getClass()} onClick={() => this.props.setStatus(this.props.type)}>{this.props.type}</button>
    )
  }
}

export default StatusButton;
