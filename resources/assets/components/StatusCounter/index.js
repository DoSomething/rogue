import React from 'react';
import './status-counter.scss';

export default (props) => (
  <div className="status-counter">
      <ul>
          <li>
              <span className="count">{props.post_totals.pending_count}</span>
              <span className="status">Pending</span>
              <a className="button -secondary">Review</a>
          </li>
          <li>
              <span className="status">Accepted</span>
              <span className="count">{props.post_totals.accepted_count}</span>
          </li>
          <li>
              <span className="status">Rejected</span>
              <span className="count">{props.post_totals.rejected_count}</span>
          </li>
      </ul>
  </div>
);

