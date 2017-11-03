import React from 'react';
import PropTypes from 'prop-types';

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

Quantity.propTypes = {
  noun: PropTypes.string.isRequired,
  // quantity: PropTypes.int.isRequired,
  verb: PropTypes.string.isRequired,
};

export default Quantity;
