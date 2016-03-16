<section data-route="$Route" data-title="$Title - $SiteConfig.Title">
  <h1>$ScheduledCourse.Course.Title</h1>
  <div>$ScheduledCourse.StartDay.Long - $ScheduledCourse.EndDay.Long</div>
  <div>$ScheduledCourse.Price.Nice</div>
  <br>
  <div>
    Attendees:<br>
    <iron-ajax
      auto
      url="$AttendeesURL"
      handle-as="json"
      last-response="{{attendees}}"></iron-ajax>
    <template is="dom-repeat" items="[[attendees]]" as="attendee">
      <span>[[attendee.FirstName]]</span>
      <span>[[attendee.Surname]]</span>
      <span>[[attendee.Age]]</span>
      <span>[[attendee.Gender]]</span><br>
    </template>
  </div>
  <br>
  Add Attendee:
  $AddStudentForm
</section>
