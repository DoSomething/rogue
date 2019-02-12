import React from 'react';

class CreateActionModal extends React.Component {
  constructor() {
    super();
    console.log('CreateActionModal');
  }

  render() {
    return (
      <div className="modal">
        <a href="#" onClick={this.props.onClose} className="modal-close-button">
          &times;
        </a>
        <div className="modal__block">
          <h3>Add Post Metadata</h3>
          <form
          // className="post-form"
          // onSubmit={this.handleOnSubmitForm}
          // ref={form => (this.form = form)}
          >
            <div className="wrapper">
              <div className="form-item">
                <label className="field-label" htmlFor="action-name">
                  Action Name
                </label>
                <input
                  className="text-field"
                  id="action-name"
                  name="action-name"
                  type="text"
                  // onChange={this.handleOnCaptionUpdate}
                  placeholder="Name your action e.g. Teens for Jeans Photo Upload"
                  // ref={input => (this.caption = input)}
                />
                <label className="field-label" htmlFor="action-noun">
                  Action Noun
                </label>
                <input
                  className="text-field"
                  id="action-noun"
                  name="action-noun"
                  type="text"
                  // onChange={this.handleOnCaptionUpdate}
                  placeholder="e.g. Jeans"
                  // ref={input => (this.caption = input)}
                />
                <label className="field-label" htmlFor="action-verb">
                  Action Verb
                </label>
                <input
                  className="text-field"
                  id="action-verb"
                  name="action-verb"
                  type="text"
                  // onChange={this.handleOnCaptionUpdate}
                  placeholder="e.g. Collected"
                  // ref={input => (this.caption = input)}
                />
              </div>
            </div>
          </form>
        </div>
      </div>
    );
  }
}

export default CreateActionModal;
