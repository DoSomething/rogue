import React from 'react';

import DropdownFilter from '../DropdownFilter';
import MultiValueFilter from '../MultiValueFilter';

class FilterBar extends React.Component {
  constructor() {
    super();

    this.state = {
      filters: {},
    };

    // The component provides a function the children components can use to update the state.
    this.updateFilters = this.updateFilters.bind(this);
  }

  updateFilters(values) {
    if (['pending', 'accepted', 'rejected'].includes(values.status)) {
      this.setState((previousState) => {
        const newState = {...previousState};
        newState.filters.status = values.status;
        return newState;
      });
    } else {
      let key = Object.keys(values)[0];

      this.setState((previousState) => {
        const newState = {...previousState};
        newState.filters.tags = values;
        return newState;
      });
    }
  }

  render() {
    // ***Rendering the children**
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
         <div className="container__block -third">
          <button className="button" onClick={() => this.props.onSubmit(this.state.filters)}>Filter</button>
         </div>
      </div>
    )
  }
}

export default FilterBar;
