<% include PageStart %>
<dom-module id="$PageElementName">
  <style is="custom-style" include="shared-styles"></style>
  <template>
    $Content
    <a href="bookings/add/?scid=1">Add</a>
  </template>
</dom-module>
<script>
(function() {
  'use strict';
  class GTPage {

    /**
     * The name of this element.
     *
     * @return {String}
     */
    get is() {
      return '$PageElementName';
    }
  }

  Polymer(GTPage);
})();
</script>

<{$PageElementName}></{$PageElementName}>
<% include PageEnd %>
