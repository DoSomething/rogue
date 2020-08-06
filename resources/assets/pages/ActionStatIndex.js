import React from 'react';

import Shell from '../components/utilities/Shell';
import ActionStatsTable from '../components/ActionStatsTable';

const ActionStatIndex = () => {
  const title = 'Action Stats';
  document.title = title;

  return (
    <Shell title={title}>
      <div className="container__block">
        <ActionStatsTable />
      </div>
    </Shell>
  );
};

export default ActionStatIndex;
