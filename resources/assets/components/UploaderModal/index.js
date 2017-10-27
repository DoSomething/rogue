import React from 'react';
import ReportbackUploader from '../ReportbackUploader';

class UploaderModal extends React.Component {
  constructor() {
    super();
  }

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
      source: 'rogue-admin',
      submissions: {
        items: [],
        messaging: this.props.success,
      },
      reportback: {},
      submitReportback: this.props.submitReportback,
      uploads: {},
      updateSignup: this.props.updateSignup,
      signup: signup,
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
