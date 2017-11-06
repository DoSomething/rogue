import React from 'react';
import { map } from 'lodash';
import PropTypes from 'prop-types';

import CampaignRow from './CampaignRow';
import EventRow from './EventRow';
import './table.scss';

class Table extends React.Component {
  render() {
    const heading = this.props.headings.map((title, index) => {
      return <th key={index} className="table__cell"><h3 className="heading -delta">{title}</h3></th>
    });

    // @TODO - Rethink this. Why are CampaignRow and EventRow different?
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

Table.propTypes = {
  headings: PropTypes.array.isRequired, // eslint-disable-line react/forbid-prop-types
  data: PropTypes.array.isRequired, // eslint-disable-line react/forbid-prop-types
  type: PropTypes.string.isRequired,
};

export default Table;
