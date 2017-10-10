import React from 'react';
import { map } from 'lodash';

import CampaignRow from './CampaignRow';
import EventRow from './EventRow';
import './table.scss';

class Table extends React.Component {
  render() {
    const heading = this.props.headings.map((title, index) => {
      return <th key={index} className="table__cell"><h3 className="heading -delta">{title}</h3></th>
    });

    const rows = this.props.data.map((content, index) => {
      if (this.props.type === 'campaigns') {
        return <CampaignRow key={index} data={content} />;
      }

      return <EventRow key={index} data={content} />;

    });

    return (
      <table className="table">
        <thead>
          <tr className="table__header">
            {heading}
          </tr>
        </thead>
        <tbody>
            {rows}
        </tbody>
      </table>
    )
  }
}

export default Table;
