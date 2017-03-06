import React from 'react';

import Header from './Header';
import Row from './Row';
import './table.scss';

class CampaignTable extends React.Component {
  render() {
    return (
      <div className="table-responsive container__block">
        <h2>Table Header</h2>
        <table className="table">
          <Header />
          <tbody>
            <Row />
            <Row />
            <Row />
            <Row />
            <Row />
          </tbody>
        </table>
      </div>
    )
  }
}

export default CampaignTable;
