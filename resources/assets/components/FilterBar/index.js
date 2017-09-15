import React from 'react';

import DropdownFilter from '../DropdownFilter';
import MultiValueFilter from '../MultiValueFilter';

class FilterBar extends React.Component {
  constructor() {
    super();

    this.state = {
      status: 'accepted',
      tags: {
        'good-photo': false,
        'good-quote': false,
        'hide-in-gallery': false,
        'good-for-sponsor': false,
        'good-for-storytelling': false,
      }
    };

    // The component provides a function the children components can use to update the state.
    this.updateFilters = this.updateFilters.bind(this);
  }

  updateFilters(value){
    if (['pending', 'accepted', 'rejected'].includes(value)) {
      this.setState({
        status: value,
      });
    }

    if (['good-photo', 'good-quote', 'hide-in-gallery', 'good-for-sponsor', 'good-for-storytelling'].includes(Object.keys(value)[0])) {
      let key = Object.keys(value)[0];

      this.setState((previousState) => {
        const newState = {...previousState};
        newState.tags[key] = Object.values(value)[0];

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
          <button className="button" onClick={() => this.props.onSubmit(this.state)}>Filter</button>
         </div>
      </div>
    )
  }
}

export default FilterBar;
