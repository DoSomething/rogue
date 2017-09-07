import React from 'react';
import MediaUploader from '../MediaUploader';
import FormMessage from '../FormMessage';
import './reportback-uploader.scss';

class ReportbackUploader extends React.Component {
  constructor(props) {
    super(props);

    this.handleOnSubmitForm = this.handleOnSubmitForm.bind(this);
    this.handleOnFileUpload = this.handleOnFileUpload.bind(this);

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

    const fileType = reportback.media.file ? reportback.media.file.type : null;

    reportback.media.type = fileType ? fileType.substring(0, fileType.indexOf('/')) : null;

    this.props.submitReportback(reportback);

    // @TODO: only reset form AFTER successful RB submission.
    this.form.reset();
    this.setState({
      media: this.defaultMediaState,
    });
  }

  render() {
    const submissions = this.props.submissions;

    return (
        <div className="reportback-uploader">
          <h2 className="heading">Upload photo</h2>

          { submissions.messaging ? <FormMessage messaging={submissions.messaging} /> : null }

          <form className="reportback-form" onSubmit={this.handleOnSubmitForm} ref={form => (this.form = form)}>
            <MediaUploader label="Add photo here" media={this.state.media} onChange={this.handleOnFileUpload} />

            <div className="wrapper">
              <div className="form-item">
                <label className="field-label" htmlFor="caption">Add a caption for the photo.</label>
                <input className="text-field" id="caption" name="caption" type="text" placeholder="60 characters or less" ref={input => (this.caption = input)} />
              </div>
            </div>

            <button className="button" type="submit">Submit a new photo</button>
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
