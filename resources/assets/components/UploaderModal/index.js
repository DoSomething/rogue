import React from 'react';
import ReportbackUploader from '../ReportbackUploader';

class UploaderModal extends React.Component {
  constructor() {
    super();

    // this.state = {
    //   quantity: null
    // };

    // this.onUpdate = this.onUpdate.bind(this);
  }

  // onUpdate(event) {
  //   this.setState({ quantity: event.target.value });
  // }

  render() {
    const signup = this.props.signup;
    const campaign = this.props.campaign;

    const photoUploaderProps = {
      campaignId: signup.campaign_id,
      campaignRunId: signup.campaign_run_id,
      northstarId: signup.northstar_id,
      noun: {
        plural: campaign.reportback_info.noun,
      },
      // @TODO: do we want this to be the admin's northstar ID so we know who submitted this?
      source: 'Rogue admin',
      submissions: {
        items: [],
        messaging: {
          // success: {
            // message: "Thanks!",
          // }
        },
      },
      reportback: {},
      submitReportback: this.props.submitReportback,
      uploads: {},
    }

    return (
      <div className="modal">
        <a href="#" onClick={this.props.onClose} className="modal-close-button">&times;</a>
        <div className="modal__block">
          <ReportbackUploader {...photoUploaderProps} />
        </div>
      </div>
    );
  }
}

export default UploaderModal;
