import React from 'react';
// import { BlockWrapper } from '../Block';
import MediaUploader from '../MediaUploader';
import FormMessage from '../FormMessage';
import './uploader.scss';

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

    return (
      <div className="modal">
        <a href="#" onClick={this.props.onClose} className="modal-close-button">&times;</a>
        <div className="modal__block">
          <h3>Photo Upload</h3>
        </div>
      </div>
    );
  }
}

export default UploaderModal;
