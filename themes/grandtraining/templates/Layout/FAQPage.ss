<section data-route="$Route" data-title="$Title - $SiteConfig.Title">
  $Content

  <% loop $FAQs %>
    <h2>$Question</h2>
    <p>$Answer</p>
  <% end_loop %>

</section>
