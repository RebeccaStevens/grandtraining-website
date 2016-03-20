<% include PageStart %>
  <style is="custom-style">
    #attendees {
      border-bottom: solid 1px var(--divider-color);
      --paper-datatable-column-header: {
        font-size: 12pt;
        font-weight: bold;
        height: 48px;
        background: var(--paper-blue-200);
      };
      --paper-datatable-cell: {
        font-size: 11pt;
      };
    }
  </style>

  <h1>$ScheduledCourse.Course.Title</h1>
  <div>$ScheduledCourse.StartDay.Long - $ScheduledCourse.EndDay.Long</div>
  <div>$ScheduledCourse.Price.Nice</div>
  <br>
  <div hidden$="[[!hasData(attendees)]]">
    <h2>Attendees:</h2>
    <iron-ajax
      auto
      url="$AttendeesURL"
      handle-as="json"
      last-response="{{attendees}}"></iron-ajax>

    <paper-datatable id="attendees" data="[[attendees]]">
      <paper-datatable-column header="First Name" property="FirstName" editable dialog width="150px" style="width: 30%;">
        <template>
          <paper-input value="{{value}}" no-label-float></paper-input>
        </template>
      </paper-datatable-column>
      <paper-datatable-column header="Surname" property="Surname" editable dialog width="150px" style="width: 30%;">
        <template>
          <paper-input value="{{value}}" no-label-float></paper-input>
        </template>
      </paper-datatable-column>
      <paper-datatable-column header="Age" property="Age" editable dialog width="90px" style="width: 15%;" align="center">
        <template>
          <paper-input value="{{value}}" type="number" min="5" max="17" no-label-float></paper-input>
        </template>
      </paper-datatable-column>
      <paper-datatable-column header="Gender" property="Gender" editable dialog width="133px" style="width: 20%;" align="center">
        <template>
          <paper-dropdown-menu no-label-float style="width: 100%;">
            <paper-menu class="dropdown-content" attr-for-selected="data-value" selected="{{value}}">
              <% loop $Genders %>
                <paper-item data-value="$Up.Genders.offsetGet($Pos(0))">$Up.Genders.offsetGet($Pos(0))</paper-item>
              <% end_loop %>
            </paper-menu>
          </paper-dropdown-menu>
        </template>
      </paper-datatable-column>
      <paper-datatable-column header="Remove" width="115px" style="width: 5%;" align="center">
        <template>
        <paper-icon-button icon="icons:clear"></paper-icon-button>
        </template>
      </paper-datatable-column>
    </paper-datatable>
  </div>

  <h2>Add Attendee:</h2>
  $AddStudentForm
<% include PageEnd %>
