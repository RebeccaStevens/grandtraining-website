(function(document) {
  'use strict';

  // Grab a reference to our auto-binding template and give it some initial binding values
  // Learn more about auto-binding templates at http://goo.gl/Dx1u2g
  let app = document.querySelector('#app');

  app.menuItems = [
    {
      title: 'Home',
      href: '/',
      imgsrc: 'images/desktop-icons/home.png'
    },
    {
      title: 'Courses',
      href: '/courses',
      imgsrc: 'images/desktop-icons/courses.png'
    },
    {
      title: 'Bookings',
      href: '/bookings',
      imgsrc: 'images/desktop-icons/bookings.png'
    },
    {
      title: 'F.A.Q.',
      href: '/faq',
      imgsrc: 'images/desktop-icons/faq.png'
    },
    {
      title: 'Contact',
      href: '/contact',
      imgsrc: 'images/desktop-icons/contact.png'
    },
    {
      title: 'About',
      href: '/about',
      imgsrc: 'images/desktop-icons/about.png'
    },
    {
      title: 'Broken',
      href: '/broken',
      imgsrc: 'images/desktop-icons/broken.png'
    }
  ];

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
  app.OnServiceWorkerInstalled = function() {
    // Check to make sure caching is actually enabled—it won't be in the dev environment.
    if (!Polymer.dom(document).querySelector('platinum-sw-cache').disabled) {
      Polymer.dom(document).querySelector('#caching-complete').show();
    }
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

})(document);
