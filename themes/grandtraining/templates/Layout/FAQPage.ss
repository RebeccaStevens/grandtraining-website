<% include PageStart %>
<dom-module id="$PageElementName">
  <style is="custom-style" include="shared-styles"></style>
  <template>
    $Content

    <% loop $FAQs %>
      <h2>$Question</h2>
      <p>$Answer</p>
    <% end_loop %>

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
