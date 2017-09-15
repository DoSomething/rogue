import React from 'react';

import DropdownFilter from '../DropdownFilter';
import MultiValueFilter from '../MultiValueFilter';

class FilterBar extends React.Component {
  constructor() {
    super();

    this.state = {
      filters: {
        status: 'accepted',
        tags: {
          'good-photo': {
            label: "Good Photo",
            active: false,
          },
          'good-quote': {
            label: "Good Quote",
            active: false,
          },
          'hide-in-gallery': {
             label: "Hide In Gallery 👻",
             active: false,
          },
          'good-for-sponsor': {
            label: "Good For Sponsor",
            active: false,
          },
          'good-for-storytelling': {
            label: "Good For Storytelling",
            active: false,
          },
        }
      }
    };

    // The component provides a function the children components can use to update the state.
    this.updateFilters = this.updateFilters.bind(this);
  }

  updateFilters(value){
    if (['pending', 'accepted', 'rejected'].includes(value)) {
      this.setState((previousState) => {
        const newState = {...previousState};
        newState.filters.status = value;
        return newState;
      });
    }

    if (['good-photo', 'good-quote', 'hide-in-gallery', 'good-for-sponsor', 'good-for-storytelling'].includes(Object.keys(value)[0])) {
      let key = Object.keys(value)[0];

      this.setState((previousState) => {
        const newState = {...previousState};
        newState.filters.tags[key].active = Object.values(value)[0];
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
