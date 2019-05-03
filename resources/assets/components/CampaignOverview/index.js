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
    const pending = this.filterCampaigns(this.props.pending);
    const etc = this.filterCampaigns(this.props.etc);

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
        <CampaignTable cause="Pending Review" campaigns={pending} />
        <CampaignTable cause="etc." campaigns={etc} />
      </div>
    );
  }
}

export default CampaignOverview;
