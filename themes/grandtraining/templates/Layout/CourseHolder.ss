<% include PageStart %>
  {$Content}<br>

  <gt-course-holder>
    <% loop Children %>
      <gt-course course-title="$Title" href="$Link">
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
      </gt-course>
    <% end_loop %>
  </gt-course-holder>
<% include PageEnd %>
