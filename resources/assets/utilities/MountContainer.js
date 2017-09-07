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

    if (components[container]) {
      // @TODO - See if reviewing is needed before doing this.
      const ComponentWithReviewing = reviewComponent(components[container], window.STATE);

      ReactDOM.render(<ComponentWithReviewing />, reactElement);

      // @TODO - if no reviewins is needed, just render the component on the page.
      // ReactDOM.render(React.createElement(components[container], {...window.STATE}), reactElement);
    }
  }
}

export default mountContainer;
