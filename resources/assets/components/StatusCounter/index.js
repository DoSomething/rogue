import React from 'react';
import './status-counter.scss';

class StatusCounter extends React.Component {
  render() {
    // const causeData = this.props;

    // const causeTables = map(causeData, (data, cause) => {
    //   return <CampaignTable key={cause} cause={cause} campaigns={data} causeData={causeData} />;
    // });

    return (
      <div className="status-counter">
          <ul>
              <li>
                  <span className="count">456</span>
                  <span className="status">Pending</span>
                  <a className="button -secondary">Review</a>
              </li>
              <li>
                  <span className="status">Approved</span>
                  <span className="count">100</span>
              </li>
              <li>
                  <span className="status">Rejected</span>
                  <span className="count">24</span>
              </li>
          </ul>
      </div>
    )
  }
}

export default StatusCounter;
