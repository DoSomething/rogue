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
    this.parseEventData = this.parseEventData.bind(this);
  }

  onUpdate(event) {
    this.setState({ quantity: event.target.value });
  }

  parseEventData(events) {
        console.log(events);
    var eventsWithChange = [];

    for(var i = 0; i < events.length; i++) {
        var current = events[i];
        var next = events[i+1];

        if (next) {
          if (current.content.quantity != next.content.quantity || current.content.why_participated != next.content.why_participated || current.content.quantity_pending != next.content.quantity_pending) {
            // If there is a difference in the record, add the next record
            // since events are ordered by most recent created_at in desc order.
            eventsWithChange.push(next);
          }
        }

    }

    return eventsWithChange;
  }

  render() {
    const signup = this.props.signup;
    const campaign = this.props['campaign'];
    const parsedEvents = this.parseEventData(this.props.signupEvents);

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
            <HistoryModalTable data={parsedEvents} />
          </div>
        </div>
        <button className="button -history" disabled={!this.state.quantity} onClick={() => this.props.onUpdate(signup, this.state.quantity)}>Save</button>
      </div>
    );
  }
}

export default HistoryModal;
