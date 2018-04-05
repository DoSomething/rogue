import React from 'react';
import PropTypes from 'prop-types';

import MediaUploader from '../MediaUploader';
import FormMessage from '../FormMessage';
import './reportback-uploader.scss';

class PostUploader extends React.Component {
  constructor(props) {
    super(props);

    this.handleOnSubmitForm = this.handleOnSubmitForm.bind(this);
    this.handleOnFileUpload = this.handleOnFileUpload.bind(this);
    this.handleOnCaptionUpdate = this.handleOnCaptionUpdate.bind(this);
    this.handleOnQuantityUpdate = this.handleOnQuantityUpdate.bind(this);
    this.handleOnWhyParticipatedUpdate = this.handleOnWhyParticipatedUpdate.bind(this);

    this.defaultMediaState = {
      file: null,
      filePreviewUrl: null,
      type: null,
      uri: null,
    };

    this.state = {
      media: this.defaultMediaState,
      caption: null,
    };
  }

  handleOnFileUpload(media) {
    this.setState({ media });
  }

  handleOnCaptionUpdate(event) {
    this.setState({ caption: event.target.value });
  }

  handleOnQuantityUpdate(event) {
    this.setState({ caption: event.target.value });
  }

  handleOnWhyParticipatedUpdate(event) {
    this.setState({ caption: event.target.value });
  }

  handleOnSubmitForm(event) {
    event.preventDefault();

    const reportback = {
      media: this.state.media,
      text: this.caption.value,
      campaignId: this.props.campaignId,
      campaignRunId: this.props.campaignRunId,
      northstarId: this.props.northstarId,
      status: 'accepted',
    };

    const signup = this.props.signup;

    if (this.quantity.value) {
      reportback.impact = this.quantity.value;
      signup.quantity = reportback.impact;
    }

    if (this.why_participated.value) {
      reportback.whyParticipated = this.why_participated.value;
      signup.why_participated = reportback.whyParticipated;
    }

    const fileType = reportback.media.file ? reportback.media.file.type : null;

    reportback.media.type = fileType ? fileType.substring(0, fileType.indexOf('/')) : null;

    this.props.submitReportback(reportback);

    // @TODO: only reset form AFTER successful RB submission.
    this.form.reset();
    this.setState({
      media: this.defaultMediaState,
    });

    this.props.updateSignup(signup);
  }

  render() {
    const submissions = this.props.submissions;

    return (
      <div className="reportback-uploader">
        <h2 className="heading">Upload photo</h2>
        <p><span className="warning">Warning:</span> after uploading a photo, it will automatically be approved. You will still need to add tags in Rogue. No email will trigger telling the user we received their photo.</p>

        { submissions.messaging ? <FormMessage messaging={submissions.messaging} /> : null }

        <form className="reportback-form" onSubmit={this.handleOnSubmitForm} ref={form => (this.form = form)}>
          <MediaUploader label="Add photo here" media={this.state.media} onChange={this.handleOnFileUpload} />

          <div className="wrapper">
            <div className="form-item">
              <label className="field-label" htmlFor="caption">Add a caption for the photo:</label>
              <input className="text-field" id="caption" name="caption" type="text" onChange={this.handleOnCaptionUpdate} placeholder="60 characters or less" ref={input => (this.caption = input)} />
            </div>
            <div className="form-item">
              <label className="field-label" htmlFor="quantity">Add a quantity:</label>
              <input className="text-field" id="quantity" name="quantity" type="text" onChange={this.handleOnQuantityUpdate} placeholder="A number, not a word! " ref={input => (this.quantity = input)} />
            </div>
            <div className="form-item">
              <label className="field-label" htmlFor="why_participated">Update why participated:</label>
              <input className="text-field" id="why_participated" name="why_participated" type="text" onChange={this.handleOnWhyParticipatedUpdate} placeholder="Optional if it already exists" ref={input => (this.why_participated = input)} />
            </div>
          </div>

          <button className="button" disabled={! this.state.media.filePreviewUrl || ! this.state.caption} type="submit">Submit a new photo</button>
        </form>
      </div>
    );
  }
}

PostUploader.propTypes = {
  campaignId: PropTypes.string.isRequired,
  campaignRunId: PropTypes.number,
  northstarId: PropTypes.string.isRequired,
  signup: PropTypes.object.isRequired, // eslint-disable-line react/forbid-prop-types
  source: PropTypes.string.isRequired,
  submitReportback: PropTypes.func.isRequired,
  submissions: PropTypes.shape({
    isFetching: PropTypes.bool,
    isStoring: PropTypes.bool,
    items: PropTypes.array,
    messaging: PropTypes.object,
    reportback: PropTypes.object,
  }).isRequired,
  updateSignup: PropTypes.func.isRequired,
};

export default PostUploader;
