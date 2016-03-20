  $Content
<% include PageStart %>

  <% loop $FAQs %>
    <h2>$Question</h2>
    <p>$Answer</p>
  <% end_loop %>

<% include PageEnd %>
