<% include PageStart %>

  <gt-course-page course-title="$Title">
    <% with $PrimaryImage.ScaleWidth(700).CropHeight(600) %>
      <img src="$URL" alt="Course Image" width="$Width" height="$Height" class="primary-image">
    <% end_with %>

    <div class="content">
      $Content
    </div>

    <%-- if there are some courses available --%>
    <% if $HolidayCourses || $AfterSchoolCourses || $SaturdayCourses %>

      <gt-course-block block-title="School Holiday Courses">
        <% loop $HolidayCourses %>
          <gt-course
            course-title="$Title"
            price="$TypicalPrice"
            min-age="$MinAge"
            max-age="$MaxAge"
            <% if $TypicalDays = 1 %>
              duration="$TypicalDays day"
            <% else %>
              duration="$TypicalDays days"
            <% end_if %>
            >
            <div class="description">{$Description}</div>

            <% if $ScheduledCourses %>
              <% loop $ScheduledCourses %>
                <gt-course-schedule start-day="$StartDay" end-day="$EndDay" price="$Price" href="$Link"></gt-course-schedule>
              <% end_loop %>
            <% end_if %>
          </gt-course>
        <% end_loop %>
      </gt-course-block>

      <gt-course-block block-title="After School Courses">
        <% loop $AfterSchoolCourses %>
          <gt-course
            course-title="$Title"
            price="$TypicalPrice"
            min-age="$MinAge"
            max-age="$MaxAge">
            <div class="description">{$Description}</div>

            <% if $ScheduledCourses %>
              <% loop $ScheduledCourses %>
                <gt-course-schedule start-day="$StartDay" end-day="$EndDay" price="$Price" href="$Link"></gt-course-schedule>
              <% end_loop %>
            <% end_if %>
          </gt-course>
        <% end_loop %>
      </gt-course-block>

      <gt-course-block block-title="Saturday Courses">
        <% loop $SaturdayCourses %>
          <gt-course
            course-title="$Title"
            price="$TypicalPrice"
            min-age="$MinAge"
            max-age="$MaxAge">
            <div class="description">{$Description}</div>

          <% if $ScheduledCourses %>
            <% loop $ScheduledCourses %>
              <gt-course-schedule start-day="$StartDay" end-day="$EndDay" price="$Price" href="$Link"></gt-course-schedule>
            <% end_loop %>
          <% end_if %>
        </gt-course>
      <% end_loop %>
    </gt-course-block>

    <%-- else if there are no courses available --%>
    <% else %>
      <h2>No Courses Currently Available</h2>
    <% end_if %>
  </div

  </gt-course-page>
<% include PageEnd %>
