(function(document) {
  'use strict';

  // Grab a reference to our auto-binding template and give it some initial binding values
  // Learn more about auto-binding templates at http://goo.gl/Dx1u2g
  let app = document.querySelector('#app');

  app.baseUrl = '/grandtraining-website/';

  app.windowTitle = document.title;

  /**
   * Set the route.
   * Requires that route to already be localy downloaded.
   *
   * @param {String} route
   */
  app.setRoute = function(route) {
    app.route = route;
    app.async(function() {
      var title = Polymer.dom(app.$pages).querySelector('.iron-selected').dataset.title;

      document.title = app.windowTitle = title;
    });
  };

  /**
   * Show a toast message to the user.
   * If no message is provided, the previous message will be shown.
   *
   * @param {string} [message] - The message to show
   */
  app.showToast = function(message) {
    if (typeof message === 'string') {
      app.$.toast.text = message;
    }
    app.$.toast.show();
  };

  /**
   * Hide the toast message currently being shown.
   */
  app.hideToast = function() {
    app.$.toast.hide();
  };

  /**
   * Tell the user that the service worker is installed.
   * Called when the service worker is installed.
   */
  app.onServiceWorkerInstalled = function() {
    // Check to make sure caching is actually enabled—it won't be in the dev environment.
    if (!Polymer.dom(document).querySelector('platinum-sw-cache').disabled) {
      Polymer.dom(document).querySelector('#caching-complete').show();
    }
  };

  /**
   * Called when a page load response is received.
   */
  app.onAjaxPageLoadResponse = function(e, request) {
    var section = request.response.querySelector('section');  // get the section tag
    Polymer.dom(app.$.pages).appendChild(section);            // add it to the pages
    app.setRoute(section.dataset.route);
  };

  /**
   * Listen for template bound event to know when bindings
   * have resolved and content has been stamped to the page
   */
  app.addEventListener('dom-change', function() {
    console.log('Our app is ready to rock!');
  });

})(document);
