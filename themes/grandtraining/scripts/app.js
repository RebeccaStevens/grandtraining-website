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
      let title = Polymer.dom(app.$pages).querySelector('.iron-selected').dataset.title;
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
   * Return whether the given param has data or not.
   *
   * @param {Object|Array} obj - The object/array to test
   * @returns {Boolean}
   */
  app.hasData = function(obj) {
    if (obj === undefined || obj === null) {
      return false;
    }
    if (obj instanceof Array) {
      return obj.length > 0;
    }
    if (typeof yourVariable === 'object') {
      return Object.keys(obj).length > 0;
    }
    console.error('Unsupported param type.');
  };

  /**
   * Returns the page for the given route.
   *
   * @param {String} route
   * @returns {HTMLElement|null}
   */
  app.getPage = function(route) {
    return app.$.pages.querySelector('section[data-route="' + route + '"]');
  };

  /**
   * Returns if the page for the given route has been loaded or not.
   *
   * @param {String} route
   * @returns {Boolean}
   */
  app.hasPage = function(route) {
    return app.getPage(route) !== null;
  };

  /**
   * Add a page downloaded with ajax to the dom.F
   *
   * @param {HTMLDocument} response - the parsed ajax response
   * @returns {HTMLElement} the page that was added
   */
  app.addPageFromAjaxResponse = function(response) {
    let section = response.body.querySelector('section');  // get the section tag

    if (!section) {
      throw new Error('invalid response.');
    }

    let route = section.dataset.route;
    let page = app.getPage(route);

    // if the page already exist, return
    if (page !== null) {
      return page;
    }

    // add the page
    let node = document.importNode(section, true);
    Polymer.dom(app.$.pages).appendChild(node);

    return node;
  };

  /**
   * Load a the page from the server.
   *
   * @param {String} path - the page's url without the query string
   * @param {String} [querystring] - the page's query string
   */
  app.loadPage = function(path, querystring='') {
    let route = path.replace(app.baseUrl, '').replace(/\/$/g, ''); // remove app.baseUrl and the trailing slash
    if (route === '') {
      route = 'home';
    }

    route = route.toLowerCase();

    if (app.hasPage(route)) {
      app.setRoute(route);
    } else {
      // download page content
      app.$.pageLoader.url = path + '?' + querystring;
      app.$.pageLoader.params = {ajax: 1};
      app.$.pageLoader.generateRequest();
    }
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
   * Called when a page section is received.
   */
  app.onAjaxPageLoaderResponse = function(e) {
    try {
      let page = app.addPageFromAjaxResponse(e.detail.xhr.response);
      app.setRoute(page.dataset.route);
    }
    catch (er) {
      location.reload();
    }
  };

  /**
   * Called when a page load response is received.
   */
  app.onAjaxPageLoaderError = app.onAjaxPageLoaderResponse; // do the same as onAjaxPageLoaderResponse

  /**
   * Listen for template bound event to know when bindings
   * have resolved and content has been stamped to the page
   */
  app.addEventListener('dom-change', function() {
    console.log('Our app is ready to rock!');
  });

})(document);
