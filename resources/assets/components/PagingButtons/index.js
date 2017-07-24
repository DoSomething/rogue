import React from 'react';

class PagingButtons extends React.Component {

  render() {
    const prev = this.props.prev;
    const next = this.props.next;
    const prevCopy = this.props.prevCopy;
    const nextCopy = this.props.nextCopy;

    return (
        <div className="container__block">
			<a href={prev}>{prevCopy}</a>
			<div className="next-page">
				<a href={next}>{nextCopy}</a>
			</div>
        </div>
    )
  }
}

export default PagingButtons;
