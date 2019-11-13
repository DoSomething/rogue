/* global window, document */

import React from 'react';
import ReactDOM from 'react-dom';

/**
 * Mount any container component to a server rendered page.
 * @param {object} components - React components to attempt to mount
 */
function mountContainer(components) {
  const reactElement = document.querySelector('[data-container]');

  if (reactElement) {
    const container = reactElement.getAttribute('data-container');

    if (components[container]) {
      ReactDOM.render(
        React.createElement(components[container], { ...window.STATE }),
        reactElement,
      );
    }
  }
}

export default mountContainer;
