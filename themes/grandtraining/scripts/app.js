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
   * Returns if the page for the given route has been loaded or not.
   *
   * @param {String} route
   * @return {Boolean}
   */
  app.hasPage = function(route) {
    return app.$.pages.querySelector('[data-route="' + route + '"') !== null;
  };

  /**
   * Add a page downloaded with ajax to the dom.F
   *
   * @param {HTMLDocument} response - the parsed ajax response
   * @returns {HTMLElement} the page that was added
   */
  app.addPageFromAjaxResponse = function(response) {
    let section = response.querySelector('section');  // get the section tag
    let dom = Polymer.dom(app.$.pages);

    // make sure the page doesn't already exist
    if (!app.hasPage(section.dataset.route)) {
      dom.appendChild(section);    // add the page
    }

    return section;
  };

  /**
   * Load a the page from the server.
   *
   * @param {String} path - the page's url
   */
  app.loadPage = function(path) {
    let route = path.replace(app.baseUrl, '').replace(/\/$/g, ''); // remove app.baseUrl and the trailing slash
    if (route === '') {
      route = 'home';
    }

    route = route.toLowerCase();

    if (app.hasPage(route)) {
      app.setRoute(route);
    } else {
      // download page content
      app.$.pageLoader.url = path;
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
  app.onAjaxPageLoaderResponse = function(e, request) {
    let page = app.addPageFromAjaxResponse(request.response);
    app.setRoute(page.dataset.route);
  };

  /**
   * Called when a page load response is received.
   */
  app.onAjaxPageLoaderError = function() {
    // download the error page if not already in the dom
    if (app.$.pages.querySelector('[data-route="page-not-found"]') === null) {
      app.$.pageLoader404.generateRequest();
    }
    // else display the error page
    else {
      app.setRoute('page-not-found');
    }
  };

  /**
   * Called when pageLoader404 receives a response.
   */
  app.onAjaxPageLoader404Response = function(e, request) {
    let page = app.addPageFromAjaxResponse(request.response);
    app.setRoute(page.dataset.route);
  };

  /**
   * Called if a error occures trying to get the page-not-found page.
   */
  app.onAjaxPageLoader404Error = function(e, request) {
    /*
     * @fixme - server always give 404 responce for pageLoader404
     * work around - this function is now doing what onAjaxPageLoader404Response should
     */
    let wrap = document.createElement('div');
    wrap.innerHTML = request.request.xhr.responseText;
    let page = app.addPageFromAjaxResponse(wrap);
    app.setRoute(page.dataset.route);

    // console.error('404 Page Not Found - Failed to get error page.');
  };

  /**
   * Listen for template bound event to know when bindings
   * have resolved and content has been stamped to the page
   */
  app.addEventListener('dom-change', function() {
    console.log('Our app is ready to rock!');
  });

})(document);
