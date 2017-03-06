import React from 'react';

import Header from './Header';
import Row from './Row';

class CampaignTable extends React.Component {
  render() {
    return (
      <div className="container__block -narrow">
        <h2>Table Header</h2>
        <table>
          <Header />
          <tbody>
            <Row />
          </tbody>
        </table>
      </div>
    )
  }
}

export default CampaignTable;
