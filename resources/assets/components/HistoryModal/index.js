import React from 'react';

class HistoryModal extends React.Component {
	constructor() {
		super();

		this.state = {
			quantity: null
		};

		this.onUpdate = this.onUpdate.bind(this);
	}

	onUpdate(event) {
		this.setState({ quantity: event.target.value });
	}

	render() {
    const post = this.props.details['post'];
    const signup = this.props.details['post']['signup'];
    const campaign = this.props.details['campaign'];

		return (
			<div className="modal">
				<a href="#" onClick={this.props.onClose} className="modal-close-button">&times;</a>
				<div className="modal__block">
					<h3>Change Quantity</h3>
					<div className="container__block -half">
						<h4>Old Quantity</h4>
						<p>{signup['quantity']} {campaign['reportback_info']['noun']} {campaign['reportback_info']['verb']}</p>
					</div>
					<div className="container__block -half">
						<h4>New Quantity</h4>
						<div className="form-item">
							<input type="text" onChange={this.onUpdate} className="text-field" placeholder="Enter # here"/>
						</div>
					</div>

					<h3>Reportback History</h3>
					<p>table of all the history goes here 📖</p>
				</div>
				<button className="button -history" disabled={!this.state.quantity} onClick={() => this.props.onUpdate(post, this.state.quantity)}>Save</button>
			</div>
		);
	}
}

export default HistoryModal;
