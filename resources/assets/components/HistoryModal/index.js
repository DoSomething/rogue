import React from 'react';
// import history modal

class HistoryModal extends React.Component {
	render() {
	    const post = this.props.details['post'];
	    const signup = this.props.details['post']['signup'];
        const campaign = this.props.details['campaign'];

		return (
			<div className="modal">
				<div className="modal-close-button" role="dialog"></div>
				<div className="modal__block">
					<h3>Change Quantity</h3>
					<div className="container__block -half">
						<h4>Old Quantity</h4>
						<p>{signup['quantity']} {campaign['reportback_info']['noun']} {campaign['reportback_info']['verb']}</p>
					</div>
					<div className="container__block -half">
						<h4>New Quantity</h4>
						<div className="form-item">
							<input type="text" className="text-field" placeholder="Enter # here"></input>
						</div>
					</div>

					<h3>Reportback History</h3>
					table of all the history goes here 📖
				</div>
			</div>
		);
	}
}

export default HistoryModal;