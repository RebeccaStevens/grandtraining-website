<% include PageStart %>

  <gt-course-page course-title="$Title">
    <% with $PrimaryImage.ScaleWidth(700).CropHeight(600) %>
      <img src="$URL" alt="Course Image" width="$Width" height="$Height" class="primary-image">
    <% end_with %>

    <div class="content">
      $Content
    </div>

    <div class="courses">
      <%-- if there are some courses available --%>
      <% if $HolidayCourses || $AfterSchoolCourses || $SaturdayCourses %>

        <%-- display the HolidayCourses --%>
        <% if $HolidayCourses %>
          <h2>Holiday Courses:</h2>
          <% loop $HolidayCourses %>
            <h3>$Title</h3>
            <p>$Description</p>
            <% if not $ScheduledCourses %>
              <p>$TypicalPrice.Nice</p>
              <p>Days: $TypicalDays</p>
            <% end_if %>
            <p>Ages: $MinAge - $MaxAge</p>

            <% if $ScheduledCourses %>
              <h4>Book Now</h4>
              <% loop $ScheduledCourses %>
                <p>$StartDay.Long - $EndDay.Long ({$Price.Nice}) <button>Book</button></p>
              <% end_loop %>
            <% else %>
            <p>There are no booking dates available for this course.</p>
            <% end_if %>
          <% end_loop %>
        <% else %>
          <h2>No Holiday Courses Currently Available</h2>
        <% end_if %>

        <%-- display the AfterSchoolCourses --%>
        <% if $AfterSchoolCourses %>
          <h2>After School Courses:</h2>
          <% loop $AfterSchoolCourses %>
            <h3>$Title</h3>
            <p>$Description</p>
            <% if not $ScheduledCourses %>
              <p>$TypicalPrice.Nice</p>
            <% end_if %>
            <p>Ages: $MinAge - $MaxAge</p>

            <% if $ScheduledCourses %>
              <h4>Book Now</h4>
              <% loop $ScheduledCourses %>
                <p>$StartDay.Long - $EndDay.Long ({$Price.Nice}) <button>Book</button></p>
              <% end_loop %>
            <% else %>
            <p>There are no booking dates available for this course.</p>
            <% end_if %>
          <% end_loop %>
        <% else %>
          <h2>No After School Courses Currently Available</h2>
        <% end_if %>

        <%-- display the SaturdayCourses --%>
        <% if $SaturdayCourses %>
          <h2>Saturday Courses:</h2>
          <% loop $SaturdayCourses %>
            <h3>$Title</h3>
            <p>$Description</p>
            <% if not $ScheduledCourses %>
              <p>$TypicalPrice.Nice</p>
            <% end_if %>
            <p>Ages: $MinAge - $MaxAge</p>

            <% if $ScheduledCourses %>
              <h4>Book Now</h4>
              <% loop $ScheduledCourses %>
                <p>$StartDay.Long - $EndDay.Long ({$Price.Nice}) <button>Book</button></p>
              <% end_loop %>
            <% else %>
            <p>There are no booking dates available for this course.</p>
            <% end_if %>
          <% end_loop %>
        <% else %>
          <h2>No Saturday Courses Currently Available</h2>
        <% end_if %>

      <%-- else if there are no courses available --%>
      <% else %>
        <h2>No Courses Currently Available</h2>
      <% end_if %>
    </div>

  </gt-course-page>
<% include PageEnd %>
