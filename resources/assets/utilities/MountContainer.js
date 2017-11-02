/* global document */
/* global window */

import React from 'react';
import ReactDOM from 'react-dom';
import reviewComponent from '../components/WithReviewing';

/**
 * Mount any container component to a server rendered page.
 * @param {object} components - React components to attempt to mount
 */
function mountContainer(components) {
  const reactElement = document.querySelector('[data-container]');

  if (reactElement) {
    const container = reactElement.getAttribute('data-container');
    const reviewing = reactElement.getAttribute('data-reviewing');

    if (components[container]) {
      // If this is a component where reviewing happens,
      // wrap the container component in the HoC for reviewing.
      if (reviewing) {
        const ComponentWithReviewing = reviewComponent(components[container], window.STATE);

        ReactDOM.render(<ComponentWithReviewing />, reactElement);

      // If there is no reviewing, just render the component on the page.
      } else {
        ReactDOM.render(
          React.createElement(
            components[container],
            { ...window.STATE },
          ), reactElement);
      }
    }
  }
}

export default mountContainer;
