import React from 'react';
import MediaUploader from '../MediaUploader';
import FormMessage from '../FormMessage';
import './reportback-uploader.scss';

class ReportbackUploader extends React.Component {
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
      caption: this.caption.value,
      campaignId: this.props.campaignId,
      campaignRunId: this.props.campaignRunId,
      northstarId: this.props.northstarId,
      source: this.props.source,
      status: 'accepted',
    };

    if (this.quantity.value) {
      reportback.impact = this.quantity.value;
    }

    if (this.why_participated.value) {
      reportback.whyParticipated = this.why_participated.value;
    }

    const fileType = reportback.media.file ? reportback.media.file.type : null;

    reportback.media.type = fileType ? fileType.substring(0, fileType.indexOf('/')) : null;

    this.props.submitReportback(reportback);

    // @TODO: only reset form AFTER successful RB submission.
    this.form.reset();
    this.setState({
      media: this.defaultMediaState,
    });

    this.props.updateQuantityOrWhyState(reportback.impact ? reportback.impact : null, reportback.whyParticipated ? reportback.whyParticipated : null);
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
                <label className="field-label" htmlFor="quantity">Update quantity:</label>
                <input className="text-field" id="quantity" name="quantity" type="text" onChange={this.handleOnQuantityUpdate} placeholder="Optional if it already exists" ref={input => (this.quantity = input)} />
              </div>
              <div className="form-item">
                <label className="field-label" htmlFor="why_participated">Update why participated:</label>
                <input className="text-field" id="why_participated" name="why_participated" type="text" onChange={this.handleOnWhyParticipatedUpdate} placeholder="Optional if it already exists" ref={input => (this.why_participated = input)} />
              </div>
            </div>

            <button className="button" disabled={!this.state.media.filePreviewUrl || !this.state.caption} type="submit">Submit a new photo</button>
          </form>
        </div>
    );
  }
}

ReportbackUploader.defaultProps = {
  noun: {
    plural: 'items',
  },
};

export default ReportbackUploader;
