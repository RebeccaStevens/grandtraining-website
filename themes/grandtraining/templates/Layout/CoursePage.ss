<section data-route="$Route" data-title="$Title">
  $PrimaryImage.SetWidth(800)
  $Content

  <h2>Holiday Courses:</h2>
  <% if $HolidayCourses %>
    <% loop $HolidayCourses %>
      <h3>$Title</h3>
      <p>$Description</p>
      <p>$Price</p>
      <p>$Days</p>
      <p>$MinAge - $MaxAge</p>
    <% end_loop %>
  <% else %>
    <p>no holiday courses</p>
  <% end_if %>

  <h2>After School Courses:</h2>
  <% if $AfterSchoolCourses %>
    <% loop $AfterSchoolCourses %>
      <h3>$Title</h3>
      <p>$Description</p>
      <p>$Price</p>
      <p>$Days</p>
      <p>$MinAge - $MaxAge</p>
    <% end_loop %>
  <% else %>
    <p>no after school courses</p>
  <% end_if %>

  <h2>Saturday Courses:</h2>
  <% if $SaturdayCourses %>
    <% loop $SaturdayCourses %>
      <h3>$Title</h3>
      <p>$Description</p>
      <p>$Price</p>
      <p>$Days</p>
      <p>$MinAge - $MaxAge</p>
    <% end_loop %>
  <% else %>
    <p>no saturday courses</p>
  <% end_if %>
</section>
