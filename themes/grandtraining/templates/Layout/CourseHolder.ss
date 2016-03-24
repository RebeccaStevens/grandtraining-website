<% include PageStart %>
  {$Content}<br>

  <gt-course-hub>
    <% loop Children %>
      <gt-course-hub-course course-title="$Title" href="$Link">
        <% with $HubImage.CroppedImage(200, 200) %>
          <img src="$URL" alt="Course Image" width="$Width" height="$Height" class="hub-image">
        <% end_with %>
        <div class="teaser">
          <% if Teaser %>
            $Teaser
          <% else %>
            {$Content.FirstParagraph}
          <% end_if %>
        </div>
      </gt-course-hub-course>
    <% end_loop %>
  </gt-course-hub>
<% include PageEnd %>
