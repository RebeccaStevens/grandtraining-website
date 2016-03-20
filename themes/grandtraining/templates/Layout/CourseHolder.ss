<% include PageStart %>

<dom-module id="$PageElementName">
  <style is="custom-style" include="shared-styles"></style>
  <template>
    $Content<br>

    <% loop Children %>
      <a href="$Link">
        $HubImage.CroppedImage(200, 200)<br>
        $Title
      </a><br>
      <% if Teaser %>
        $Teaser<br>
      <% else %>
        $Content.FirstParagraph<br>
      <% end_if %>
      <br>
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
