(function(document) {
  'use strict';

  // Grab a reference to our auto-binding template and give it some initial binding values
  // Learn more about auto-binding templates at http://goo.gl/Dx1u2g
  let app = document.querySelector('#app');

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
   * Listen for template bound event to know when bindings
   * have resolved and content has been stamped to the page
   */
  app.addEventListener('dom-change', function() {
    console.log('Our app is ready to rock!');
  });

  /**
   * Listen for when imports are loaded and elements have been registered
   */
  window.addEventListener('WebComponentsReady', function() {

  });

  /*
   * Scroll page to top and expand header
   */
  app.scrollPageToTop = function() {
    app.$.headerPanelMain.scrollToTop(true);
  };

  /*
   * Close the drawer
   */
  app.closeDrawer = function() {
    app.$.paperDrawerPanel.closeDrawer();
  };

})(document);
