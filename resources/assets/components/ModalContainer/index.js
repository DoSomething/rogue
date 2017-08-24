import React from 'react';
import classnames from 'classnames';
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

export default ModalContainer;
