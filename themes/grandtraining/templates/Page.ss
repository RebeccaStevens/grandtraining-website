<!doctype html>
<html>
<head>
  <% base_tag %>
  <meta charset="utf-8">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Polymer Starter Kit" />

<title><% if $Title != 'Home' %>$Title - <% end_if %>$SiteConfig.Title</title>

  <!-- Chrome for Android theme color -->
  <meta name="theme-color" content="#2E3AA1">

  <!-- Web Application Manifest -->
  <link rel="manifest" href="$ThemeDir/manifest.json">

  <!-- Tile color for Win8 -->
  <meta name="msapplication-TileColor" content="#3372DF">

  <!-- Add to homescreen for Chrome on Android -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="application-name" content="PSK">
  <link rel="icon" sizes="16x16" href="$ThemeDir/images/icons/icon-16x16.png">
  <link rel="icon" sizes="24x24" href="$ThemeDir/images/icons/icon-24x24.png">
  <link rel="icon" sizes="32x32" href="$ThemeDir/images/icons/icon-32x32.png">
  <link rel="icon" sizes="48x48" href="$ThemeDir/images/icons/icon-64x64.png">
  <link rel="icon" sizes="128x128" href="$ThemeDir/images/icons/icon-128x128.png">
  <link rel="icon" sizes="192x192" href="$ThemeDir/images/icons/chrome-touch-icon-192x192.png">

  <!-- Add to homescreen for Safari on iOS -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="$SiteConfig.Title">
  <link rel="apple-touch-icon" href="$ThemeDir/images/icons/apple-touch-icon.png">

  <!-- Tile icon for Win8 (144x144) -->
  <meta name="msapplication-TileImage" content="$ThemeDir/images/icons/ms-touch-icon-144x144.png">

  <link rel="stylesheet" href="$ThemeDir/styles/main.css">

  <script async src="$ThemeDir/bower_components/webcomponentsjs/webcomponents-lite.js"></script>
  <link rel="import" href="$ThemeDir/elements/elements.html">

  <style is="custom-style" include="shared-styles"></style>
</head>

<body unresolved class="fullbleed layout vertical">
  <span id="browser-sync-binding"></span>

  <template is="dom-bind" id="app">

    <gt-monitor class="no-select">
      <!-- desktop icons -->
      <% loop $Menu(1) %>
        <gt-desktop-item href="$Link" linking-mode="$LinkingMode" class="on-desktop">
          <img src="$ThemeDir/images/desktop-icons/{$URLSegment}.png">
          <span>$MenuTitle</span>
        </gt-desktop-item>
      <% end_loop %>

      <gt-window window-title="[[windowTitle]]" maximized class="flex">
        <iron-icon src="$ThemeDir/images/icons/icon-16x16.png" class="window-icon"></iron-icon>

        <gt-application-menu name="File" class="ribbon-menu">
          <gt-application-menu-item><iron-icon icon="polymer"></iron-icon>Item 1</gt-application-menu-item>
          <gt-application-menu-item><iron-icon icon="polymer"></iron-icon>Item 2</gt-application-menu-item>
          <gt-application-menu-item><iron-icon icon="polymer"></iron-icon>Item 3</gt-application-menu-item>
          <gt-application-menu-item><iron-icon icon="polymer"></iron-icon>Item 4</gt-application-menu-item>
        </gt-application-menu>

        <gt-ribbon name="Social" class="ribbon-tab">
          <% with $SiteConfig %>
            <% if $SocialLinks %>
              Social Links:
              <ul class="social-networks">
              <% if $FacebookLink %>
                <li><a href="$FacebookLink">Facebook</a></li>
              <% end_if %>
              <% if $TwitterLink %>
                <li><a href="$TwitterLink">Twitter</a></li>
              <% end_if %>
              <% if $GoogleLink %>
                <li><a href="$GoogleLink">Google</a></li>
              <% end_if %>
              <% if $YouTubeLink %>
                <li><a href="#">YouTube</a></li>
              <% end_if %>
              </ul>
            <% end_if %>
          <% end_with %>
        </gt-ribbon>
        <gt-ribbon name="Tab 3" class="ribbon-tab">
          Tab 3 Content
        </gt-ribbon>

        <!-- content -->
        <div class="selectable">
          <iron-ajax id="pageLoader"
            handle-as="document"
            on-response="onAjaxPageLoadResponse"></iron-ajax>
          <neon-animated-pages id="pages" class="fit" selected="[[route]]" attr-for-selected="data-route">
            $Layout
            <section data-route="security/login" data-title="$SiteConfig.Title Admin Login">
              $Form
            </section>
          </neon-animated-pages>
        </div>
      </gt-window>
    </gt-monitor>

    <!-- A general paper-toast element used to display a message to the user -->
    <paper-toast id="toast" class="layout horizontal">
      <span class="flex"></span>
      <span class="toast-hide-button" role="button" tabindex="0" on-tap="hideToast">Ok</span>
    </paper-toast>

    <%-- include ServiceWorker --%>

  </template>

  <script src="$ThemeDir/scripts/app.js"></script>
</body>
</html>
