import React from 'react';
import './user-export.scss';

class UserExport extends React.Component {

	render () {
		return (
			<div className="user-export">
				<h1 className="heading -delta">User Export</h1>
				<p>Download a .csv of all users who have opted to receive additional messaging.</p>
				<div>
				  <a className="button -secondary" href={`/exports/${this.props.campaign.id}`}>📩 Export</a>
				</div>
			</div>
		)
	}
}
export default UserExport;
