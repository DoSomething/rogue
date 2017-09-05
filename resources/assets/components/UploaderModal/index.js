import React from 'react';
// import { BlockWrapper } from '../Block';
import ReportbackUploader from '../ReportbackUploader';

class UploaderModal extends React.Component {
  constructor() {
    super();
    console.log(this.props);

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

    const photoUploaderProps = {
      campaignId: "1JVycvW4XqEc6oGwciCW42",
      legacyCampaignId: "7831",
      noun: {
        plural: "social posts",
        singular: "social post"
      },
      quantityOverride: null,
      submissions: {
        isFetching: false,
        isStoring: false,
        items: [],
        messaging: {
          success: {
            message: "Thanks!",
          }
        },
      },
      reportback: {

      },
      submitReportback: this.props.submitReportback,
      uploads: {},
    }

    return (
      <div className="modal">
        <a href="#" onClick={this.props.onClose} className="modal-close-button">&times;</a>
        <div className="modal__block">
          <h3>Photo Upload</h3>
          <ReportbackUploader {...photoUploaderProps} />
        </div>
      </div>
    );
  }
}

export default UploaderModal;
