import React from 'react';
import PropTypes from 'prop-types';

import './modal-container.scss';

const ModalContainer = (props) => {
  if (! props.children) {
    return null;
  }

  return (
    <div className="modal-container">
      {props.children}
    </div>
  );
};

ModalContainer.propTypes = {
  children: PropTypes.node,
};

ModalContainer.defaultProps = {
  children: null,
};

export default ModalContainer;
