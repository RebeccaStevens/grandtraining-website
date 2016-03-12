<section data-route="$Route" data-title="$Title">

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
</section>
