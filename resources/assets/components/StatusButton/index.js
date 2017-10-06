import React from 'react';
import classnames from 'classnames';

import './status-button.scss';

class StatusButton extends React.Component {
  constructor() {
    super();

    this.state = {
      'sending': false,
    }

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick () {
    this.setState({ 'sending' : true });

    this.props.setStatus(this.props.type)
      .then(() => {
        this.setState({ 'sending' : false });
      });
  }

  render() {
    return (
      <button className={classnames('button', {'-outlined-button' : !this.state.sending},  `-${this.props.type}`, {'is-selected' : this.props.status == this.props.type}, {'is-loading' : this.state.sending})} onClick={() => this.handleClick()}>
        {this.props.label}
      </button>
    )
  }
}

export default StatusButton;
