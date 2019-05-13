import React from 'react';

import CampaignTable from '../CampaignTable';

class CampaignOverview extends React.Component {
  constructor(props) {
    super(props);

    this.state = { search: '' };

    this.onChange = this.onChange.bind(this);
  }

  /**
   * Update state when search term changes.
   *
   * @param {*} event
   */
  onChange(event) {
    this.setState({ search: event.target.value });
  }

  /**
   * Filter the given campaigns by current search term.
   *
   * @return {Array}
   */
  filterCampaigns(campaigns) {
    const search = this.state.search.toLowerCase();

    return campaigns.filter(campaign => {
      if (this.state.search === '') {
        return true;
      }

      const matchesId = campaign.id.toString().includes(search);

      const matchesTitle = campaign.internal_title
        .toLowerCase()
        .includes(search);

      const matchesCause = campaign.cause.some(cause =>
        cause.toLowerCase().includes(search),
      );

      return matchesId || matchesTitle || matchesCause;
    });
  }

  /**
   * Render the campaign overview!
   *
   * @return {React.Element}
   */
  render() {
    const { pending, etc } = this.props;

    const noCampaignsMessage = <p>No campaigns to display!</p>;

    return (
      <div className="container">
        <div className="container__block -half">
          <input
            type="text"
            className="text-field -search"
            placeholder="Filter by campaign ID, name, cause..."
            onChange={this.onChange}
          />
        </div>
        <div className="container__block">
          <h3>Pending Review</h3>
          <p>
            These campaigns are currently active &amp; have posts pending
            review:
          </p>
        </div>
        <div className="container__block">
          {pending ? (
            <CampaignTable campaigns={this.filterCampaigns(pending)} />
          ) : (
            noCampaignsMessage
          )}
        </div>
        <div className="container__block">
          <h3>Everything Else</h3>
          <p>These campaigns are either closed or have no pending posts:</p>
        </div>
        <div className="container__block">
          {etc ? (
            <CampaignTable campaigns={this.filterCampaigns(etc)} />
          ) : (
            noCampaignsMessage
          )}
        </div>
      </div>
    );
  }
}

export default CampaignOverview;
