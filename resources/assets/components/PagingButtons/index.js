import React from 'react';

class PagingButtons extends React.Component {

  render() {
    const prev = this.props.prev;
    const next = this.props.next;

    const showPrev = (prev === null) ? "paging -no-show" : "paging";
    const showNext = (next === null) ? "paging -next-page -noShow" : "paging -next-page";


    return (
        <div className="container__block">
          <div className={showPrev}>
            <a href={prev}>&larr; previous</a>
          </div>
          <div className={showNext}>
            <a href={next}>next &rarr;</a>
          </div>
        </div>
    )
  }
}

export default PagingButtons;
