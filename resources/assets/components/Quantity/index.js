import React from 'react';

class Quantity extends React.Component {
  render() {
    return (
      <div className="container__row figure -left -center">
        <div className="figure__media">
          <div className="quantity">{this.props.quantity}</div>
        </div>
        <div className="figure__body">
          {this.props.noun && this.props.verb ?
            <h4 className="reportback-noun-verb">{this.props.noun} {this.props.verb}</h4>
          : null}
        </div>
      </div>
    )
  }
}

export default Quantity;
