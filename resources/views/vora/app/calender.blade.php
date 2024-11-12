@extends('layouts.default')

@section('content')
<div class="container-fluid">
	<!-- Add Order -->
	<div class="modal fade" id="addOrderModalside">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Create Project</h5>
					<button type="button" class="close" data-bs-dismiss="modal"><span>&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						@csrf
						<div class="form-group">
							<label class="text-black font-w500">Project Name</label>
							<input type="text" class="form-control">
						</div>
						<div class="form-group">
							<label class="text-black font-w500">Deadline</label>
							<input type="date" class="form-control">
						</div>
						<div class="form-group">
							<label class="text-black font-w500">Client Name</label>
							<input type="text" class="form-control">
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-primary">CREATE</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="page-titles">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="javascript:void(0)">App</a></li>
		<li class="breadcrumb-item active"><a href="javascript:void(0)">Calendar</a></li>
	</ol>
	</div>
	<!-- row -->
	<div class="row">
		<div class="col-xl-3">
			<div class="card">
				<div class="card-body">
					<h4 class="card-intro-title">Calendar</h4>

					<div class="">
						<div id="external-events" class="my-3">
							<p>Drag and drop your event or click in the calendar</p>
							<div class="external-event fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" data-class="bg-primary ">
								<i class="fa fa-move"></i>New Theme Release</div>
							<div class="external-event fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" data-class="bg-success"><i class="fa fa-move"></i>My Event
							</div>
							<div class="external-event fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" data-class="bg-warning"><i class="fa fa-move"></i>Meet manager</div>
							<div class="external-event fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event" data-class="bg-dark"><i class="fa fa-move"></i>Create New theme</div>
						</div>
						<!-- checkbox -->
						<div class="checkbox custom-control checkbox-event custom-checkbox pt-3 pb-5">
							<input type="checkbox" class="custom-control-input" id="drop-remove">
							<label class="custom-control-label" for="drop-remove">Remove After Drop</label>
						</div>
						<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-category" class="btn btn-primary btn-event w-100">
							<span class="align-middle me-1"><i class="ti-plus"></i></span> Create New
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-9">
			<div class="card">
				<div class="card-body">
					<div id="calendar" class="app-fullcalendar"></div>
				</div>
			</div>
		</div>
		<!-- BEGIN MODAL -->
		<div class="modal fade none-border" id="event-modal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title"><strong>Add New Event</strong></h4>
					</div>
					<div class="modal-body"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-success save-event waves-effect waves-light">Create
							event</button>

						<button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-bs-dismiss="modal">Delete</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal Add Category -->
		<div class="modal fade none-border" id="add-category">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title"><strong>Add a category</strong></h4>
					</div>
					<div class="modal-body">
						<form>
							@csrf
							<div class="row">
								<div class="col-md-6">
									<label class="control-label">Category Name</label>
									<input class="form-control form-white" placeholder="Enter name" type="text" name="category-name">
								</div>
								<div class="col-md-6">
									<label class="control-label">Choose Category Color</label>
									<select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
										<option value="success">Success</option>
										<option value="danger">Danger</option>
										<option value="info">Info</option>
										<option value="pink">Pink</option>
										<option value="primary">Primary</option>
										<option value="warning">Warning</option>
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-danger waves-effect waves-light save-category" data-bs-dismiss="modal">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
@endsection

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
	  var Calendar = FullCalendar.Calendar;
	  var Draggable = FullCalendar.Draggable;
  
	  var containerEl = document.getElementById('external-events');
	  var calendarEl = document.getElementById('calendar');
	  var checkbox = document.getElementById('drop-remove');
  
	  // initialize the external events
	  // -----------------------------------------------------------------
  
	  new Draggable(containerEl, {
		itemSelector: '.fc-event',
		eventData: function(eventEl) {
		  return {
			title: eventEl.innerText
		  };
		}
	  });
  
	  // initialize the calendar
	  // -----------------------------------------------------------------
  
	  var calendar = new Calendar(calendarEl, {
		headerToolbar: {
		  left: 'prev,next today',
		  center: 'title',
		  right: 'dayGridMonth,timeGridWeek,timeGridDay'
		},
		editable: true,
		droppable: true, // this allows things to be dropped onto the calendar
		drop: function(info) {
		  // is the "remove after drop" checkbox checked?
		  if (checkbox.checked) {
			// if so, remove the element from the "Draggable Events" list
			info.draggedEl.parentNode.removeChild(info.draggedEl);
		  }
		}
	  });
  
	  calendar.render();
	});
</script>
@endpush