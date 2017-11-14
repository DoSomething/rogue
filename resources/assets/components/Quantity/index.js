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
    );
  }
}

Quantity.propTypes = {
  noun: PropTypes.string,
  // @TODO - figure out why this comes in two different ways.
  quantity: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.number,
  ]),
  verb: PropTypes.string,
};

Quantity.defaultProps = {
  noun: 'things',
  quantity: 0,
  verb: 'done',
};

export default Quantity;
