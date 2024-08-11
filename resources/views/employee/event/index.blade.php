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

@endsection

@section('extra-js')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script>
    $(document).ready(function () {
    
    var SITEURL = "{{ url('/') }}";

    var cachedEvents = [];
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    fetchEvents();

        function fetchEvents() {
        $.ajax({
            url: SITEURL + "/employee/render",
            success: function (data) {
            cachedEvents = data; 
            renderCalendar(cachedEvents);
            }
        });
    }

    function renderCalendar(events) {
        console.log(events)
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: events,
            textColor: 'white',
            displayEventTime: false,
            editable: true,
            resizable: true,
            droppable: true, 
            eventColor: 'orange',
            eventRender: function (event, element, view) {
                if (event.allDay === 'false') {
                    event.allDay = true;
                } 
            },
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                var title = prompt('Event Title:');
                if (title) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                    $.ajax({
                        url: SITEURL + "/employee/eventsAjax",
                        data: {
                            title: title,
                            start_date: start,
                            end_date: end,
                            type: 'add'
                        },
                        type: "POST",
                        success: function (data) {
                            displayMessage("Event Created Successfully");

                            calendar.fullCalendar('renderEvent',
                                {
                                    id: data.id,
                                    title: title,
                                    start: start,
                                    end: end,
                                    allDay: allDay
                                },true);

                            calendar.fullCalendar('unselect');
                        }
                    });
                }
            },
            eventDrop: function (event, delta) {
                var start = $.fullCalendar.formatDate(event.start, "YYYY-MM-DD");
                var end = $.fullCalendar.formatDate(event.end, "YYYY-MM-DD");
                console.log("Start Date:", start);
                console.log("End Date:", end);
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
                        console.log(response);
                        displayMessage("Event Updated Successfully");
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
                        }
                    });
                }
            },
            
            eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
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
                    },
                });
            },
        })
    }
})


function displayMessage(message) {
    toastr.success(message, 'Event');
} 
</script>
@endsection