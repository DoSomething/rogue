import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';

class EventRow extends React.Component {
  constructor() {
    super();

    this.createEventRow = this.createEventRow.bind(this);
  }

  createEventRow(event) {
    const newQuantity = event.content.quantity ? event.content.quantity : event.content.quantity_pending;

    const row = [
      {
        title: newQuantity,
      },
      {
        title: event.content.why_participated,
      },
      {
        title: event.content.updated_at,
      },
      {
        title: event.user,
      }
    ];

    return row;
  }

  render() {
    const content = this.createEventRow(this.props.data);

    return (
      <tr className="table__row">
       {content.map(function(cell) {
          return <td className="table__cell">{cell.title}</td>
        })}
      </tr>
    )
  }
}

EventRow.propTypes = {
  data: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
};

export default EventRow;
