import React from 'react';
import { map } from 'lodash';

import HistoryModalTable from '../HistoryModalTable';

class HistoryModal extends React.Component {
  constructor() {
    super();

    this.state = {
      quantity: null
    };

    this.onUpdate = this.onUpdate.bind(this);
  }

  onUpdate(event) {
    this.setState({ quantity: event.target.value });
  }

  render() {
    const signup = this.props.signup;
    const campaign = this.props['campaign'];

      // console.log(this.props.signupEvents);
    // const historyModalTable = map(this.props.signupEvents, (signupEvent, index) => {
    //   return <HistoryModalTable key={index} data={signupEvent}/>;
    // });

    // const historyModalTable = return <HistoryModalTable data={this.props.signupEvents}/>;

    return (
      <div className="modal">
        <a href="#" onClick={this.props.onClose} className="modal-close-button">&times;</a>
        <div className="modal__block">
          <h3>Change Quantity</h3>
          <div className="container__block -half">
            <h4>Old Quantity</h4>
            <p>{signup['quantity']} {campaign['reportback_info']['noun']} {campaign['reportback_info']['verb']}</p>
          </div>
          <div className="container__block -half">
            <h4>New Quantity</h4>
            <div className="form-item">
              <input type="text" onChange={this.onUpdate} className="text-field" placeholder="Enter # here"/>
            </div>
          </div>

          <h3>Reportback History</h3>
          <p>A log of the 20 most recent signup events! 📖</p>
          <div className="container">
            <HistoryModalTable data={this.props.signupEvents} />
          </div>
        </div>
        <button className="button -history" disabled={!this.state.quantity} onClick={() => this.props.onUpdate(signup, this.state.quantity)}>Save</button>
      </div>
    );
  }
}

export default HistoryModal;
