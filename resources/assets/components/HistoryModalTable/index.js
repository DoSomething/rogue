import React from 'react';
import { map } from 'lodash';

import HistoryModalRow from './HistoryModalRow';
import '../Table/table.scss';

class HistoryModalTable extends React.Component {
  render() {
    const headings = [
      'Quantity',
      'Why Participated',
      'Updated At',
      'User',
    ];

    const heading = headings.map((title, index) => {
      return <th key={index} className="table__cell"><h3 className="heading -delta">{title}</h3></th>
    });
    const rows = this.props.data.map((content, index) => {
      return <HistoryModalRow key={index} data={content}/>;
    });

    return (
      <div className="table-responsive container__block">
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
      </div>
    )
  }
}

export default HistoryModalTable;
