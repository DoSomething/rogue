import React from 'react';

import DropdownFilter from '../DropdownFilter';
import TagsFilter from '../TagsFilter';

class FilterBar extends React.Component {
  constructor() {
    super();

    this.state = {};

    // The component provides a function the children components can use to update the state.
    this.updateFilters = this.updateFilters.bind(this);
    // this.setStatus = this.setStatus.bind(this);
    // this.setTags = this.setTags.bind(this);
  }

  updateFilters(values){
    if (['pending', 'accepted', 'rejected'].includes(values)) {
      this.setState({
        status: values,
      });
    }
  }
  // setStatus(event){
  //   this.setState((previousState) => {
  //     const newState = {...previousState};
  //     newState.status = event;

  //     return newState;
  //   });
  // }

  // setTags(tag, bool){
  //   this.setState((previousState) => {
  //     const newState = {...previousState};
  //     newState.tags[tag] = bool;

  //     return newState;
  //   });
  // }
  render() {
    // ***Rendering the children**

    // In this case DropdownFilter and MultiValueFilter are children of the FilterBar as layed out in Campaign Single.
    // This maps over any child of the FilterBar component and makes a copy of it so we can send props
    // (like our function to update state) to it from this component.
    const childrenWithProps = React.Children.map(this.props.children,
     (child) => React.cloneElement(child, {
       updateFilters: this.updateFilters
     })
    );

    // Render the new children.
    return (
      <div>
         <div>{childrenWithProps}</div>
         <button className="button" onClick={() => this.props.onSubmit(this.state)}>Filter</button>
      </div>
    )
  }



  //   return (
  //     <div className="container">
  //       <div className="container__block -third">
  //         <h4>Post Status</h4>
  //         <div className="select">
  //           <StatusFilter setStatus={this.setStatus} />
  //         </div>
  //       </div>
  //       <div className="container__block -third">
  //         <h4>Tags</h4>
  //           <TagsFilter onTag={this.setTags} />
  //       </div>
  //       <div className="container__block -third">
  //         <button className="button" onClick={() => this.props.setFilters(this.state)}>Filter</button>
  //       </div>
  //     </div>
  //   )
  // }
}

export default FilterBar;
