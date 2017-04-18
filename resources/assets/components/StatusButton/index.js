import React from 'react';
import './status-button.scss';
import classnames from 'classnames';

class StatusButton extends React.Component {
  // Ideal setStatus would be defined on this component and would set some sort of global state via redux
  render() {
    return (
      <button className={classnames('button', `-${this.props.type}`)} onClick={() => this.props.setStatus(this.props.type)}>{this.props.type}</button>
    )
  }
}

export default StatusButton;
