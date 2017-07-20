import React from 'react';
import './status-counter.scss';

export default (props) => (
  <div className="status-counter">
      <ul>
          <li>
              <span className="count">{props.postTotals.pending_count}</span>
              <span className="status">Pending</span>
              <a className="button -secondary" href={`/campaigns/${props.campaign.id}/inbox`}>Review</a>
          </li>
          {/* @TODO - add back in when we deal with pagination on the single campaign view
          <li>
              <span className="status">Accepted</span>
              <span className="count">{props.postTotals.accepted_count}</span>
          </li>
          <li>
              <span className="status">Rejected</span>
              <span className="count">{props.postTotals.rejected_count}</span>
          </li>*/}
      </ul>
  </div>
);

