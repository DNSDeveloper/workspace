@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">List of Event</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}">Employee Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        List of Event
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">List of Event</h3>
                    </div>
                    <div class="card-body">
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Add Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="eventForm">
                    <div class="form-group">
                        <label for="eventTitle">Event Title</label>
                        <input type="text" class="form-control" id="eventTitle" required>
                    </div>
                    <div class="form-group">
                        <label for="eventColor">Event Color</label>
                        <input type="color" class="form-control" id="eventColor" value="#3788d8">
                    </div>
                    <input type="hidden" id="eventStart">
                    <input type="hidden" id="eventEnd">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveEvent">Save Event</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script>
    $(document).ready(function () {
    
    var SITEURL = "{{ url('/') }}";
    var calendar;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    calendar = $('#calendar').fullCalendar({
        editable: true,
        events: SITEURL + "/employee/render",
        displayEventTime: false,
        editable: true,
        resizable: true,
        droppable: true,
        eventRender: function (event, element, view) {
            if (event.allDay === 'true') {
                event.allDay = true;
            } else {
                event.allDay = false;
            }
        },
        selectable: true,
        selectHelper: true,
        select: function (start, end, allDay) {
            $('#eventStart').val($.fullCalendar.formatDate(start, "Y-MM-DD"));
            $('#eventEnd').val($.fullCalendar.formatDate(end, "Y-MM-DD"));
            $('#eventModal').modal('show');
        },
        eventDrop: function (event, delta) {
            var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
            var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");
            $.ajax({
                url: SITEURL + '/employee/eventsAjax',
                data: {
                    title: event.title,
                    start_date: start,
                    end_date: end,
                    id: event.id,
                    type: 'update'
                },
                type: "POST",
                success: function (response) {
                    displayMessage("Event Updated Successfully");
                    calendar.fullCalendar('refetchEvents');
                },
            });
        },
        eventClick: function (event) {
            var deleteMsg = confirm("Do you really want to delete?");
            if (deleteMsg) {
                $.ajax({
                    type: "POST",
                    url: SITEURL + '/employee/eventsAjax',
                    data: {
                            id: event.id,
                            type: 'delete'
                    },
                    success: function (response) {
                        calendar.fullCalendar('removeEvents', event.id);
                        displayMessage("Event Deleted Successfully");
                        calendar.fullCalendar('refetchEvents');
                    }
                });
            }
        },
        
        eventResize: function (event, delta) {
            var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
            var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");

            $.ajax({
                url: SITEURL + '/employee/eventsAjax',
                data: {
                    title: event.title,
                    start_date: start,
                    end_date: end,
                    id: event.id,
                    type: 'update'
                },
                type: "POST",
                success: function (response) {
                    displayMessage("Event Updated Successfully");
                    calendar.fullCalendar('refetchEvents');
                },
            });
        },
    });

    $('#saveEvent').on('click', function() {
        var title = $('#eventTitle').val();
        var color = $('#eventColor').val();
        var start = $('#eventStart').val();
        var end = $('#eventEnd').val();

        if (title) {
            $.ajax({
                url: SITEURL + "/employee/eventsAjax",
                data: {
                    title: title,
                    start_date: start,
                    end_date: end,
                    color: color,
                    type: 'add'
                },
                type: "POST",
                success: function (data) {
                    displayMessage("Event Created Successfully");
                    $('#eventModal').modal('hide');
                    calendar.fullCalendar('refetchEvents');
                    $("#eventTitle").val("")
                    $("#eventColor").val("")
                }
            });
        }
    });
});

function displayMessage(message) {
    toastr.success(message, 'Event');
} 
</script>
@endsection