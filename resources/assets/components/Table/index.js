import React from 'react';
import { map } from 'lodash';

import Row from './Row';
import './table.scss';

class Table extends React.Component {
  render() {
    const heading = this.props.headings.map((title, index) => {
      return <th key={index} className="table__cell"><h3 className="heading -delta">{title}</h3></th>
    });

    const rows = this.props.data.map((content, index) => {
      return <Row key={index} campaign={content} campaign_id={content ? content.id : ''} pending={content ? content.pending_count : 0} />;
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
