import React from 'react';
import PropTypes from 'prop-types';
import PostUploader from '../PostUploader';

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
        plural: 'items',
      },
      source: 'rogue-admin',
      submissions: {
        items: [],
        messaging: this.props.success,
      },
      post: {},
      submitPost: this.props.submitPost,
      uploads: {},
      updateSignup: this.props.updateSignup,
      signup,
    };

    return (
      <div className="modal">
        <a href="#" onClick={this.props.onClose} className="modal-close-button">
          &times;
        </a>
        <div className="modal__block">
          <PostUploader {...photoUploaderProps} />
        </div>
      </div>
    );
  }
}

UploaderModal.propTypes = {
  signup: PropTypes.shape({
    campaign_id: PropTypes.string,
    campaign_run_id: PropTypes.number,
    northstar_id: PropTypes.string,
  }).isRequired,
  campaign: PropTypes.shape({
    reportback_info: PropTypes.object,
  }).isRequired,
  success: PropTypes.object, // eslint-disable-line react/forbid-prop-types
  submitPost: PropTypes.func.isRequired,
  updateSignup: PropTypes.func.isRequired,
  onClose: PropTypes.func.isRequired,
};

UploaderModal.defaultProps = {
  success: null,
};

export default UploaderModal;
