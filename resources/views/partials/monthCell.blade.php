{{-- Needs variables: i, date, id --}}

@if(Session::has('userGroup')
    AND (Session::get('userGroup') == 'marketing'
    OR Session::get('userGroup') == 'clubleitung'))
    <a href="{{ Request::getBasePath() }}/
			event/
			{{ $date['year']}}/
			{{ $date['month'] }}/
			{{ strftime("%d", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}/
			0/
			create">
	{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}
	</a>
@else
	{{ date("j.", strtotime($i - $date['startDay']." day", $date['startStamp'])) }}
@endif


@foreach($events as $clubEvent)
	@if($clubEvent->evnt_date_start === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))

		{{-- Filter --}}
        @if ( empty($clubEvent->evnt_show_to_club) )
        	{{-- Workaround for older events: if filter is empty - use event club data instead --}}
            <div class="filter {!! $clubEvent->getPlace->plc_title !!}  word-break">
       	@else
       		{{-- Normal scenario: add a css class accordnig to filter data --}}
			<div class="filter {!! in_array( "bc-Club", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Club" : false !!} {!! in_array( "bc-Café", json_decode($clubEvent->evnt_show_to_club) ) ? "bc-Café" : false !!} word-break">
		@endif
				{{-- guests see private events as placeholders only, so check if user is logged in --}}
				@if(!Session::has('userId'))
					
					{{-- show only a placeholder for private events --}}
					@if($clubEvent->evnt_is_private)

						<div class="cal-event dark-grey">
							<i class="fa fa-eye-slash white-text"></i>
							<span class="white-text">Internes Event</span>	
						</div>

					{{-- show everything for public events --}}
					@else
						@if     ($clubEvent->evnt_type == 1)
							<div class="cal-event calendar-public-info">
						@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
							<div class="cal-event calendar-public-task">
						@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
							<div class="cal-event calendar-public-marketing">
						@elseif ($clubEvent->getPlace->id == 2)
							<div class="cal-event calendar-public-event-bc-club">
						@elseif ($clubEvent->getPlace->id == 3)
							<div class="cal-event calendar-public-event-bc-cafe">
						@endif
							@include("partials.event-marker", $clubEvent)
						 	<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
								{{{ $clubEvent->evnt_title }}}
							</a>
						</div>

					@endif

				{{-- show everything for members, but switch the color theme according to event type --}}
				@else

					@if($clubEvent->evnt_is_private)
						@if     ($clubEvent->evnt_type == 1)
							<div class="cal-event calendar-internal-info">
						@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
							<div class="cal-event calendar-internal-task">
						@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
							<div class="cal-event calendar-internal-marketing">
						@elseif ($clubEvent->getPlace->id == 2)
							<div class="cal-event calendar-internal-event-bc-club">
						@elseif ($clubEvent->getPlace->id == 3)
							<div class="cal-event calendar-internal-event-bc-cafe">
						@else
							{{-- DEFAULT --}}
							<div class="cal-event dark-grey">
						@endif
					@else
						@if     ($clubEvent->evnt_type == 1)
							<div class="cal-event calendar-public-info">
						@elseif ($clubEvent->evnt_type == 6 OR $clubEvent->evnt_type == 9)
							<div class="cal-event calendar-public-task">
						@elseif ($clubEvent->evnt_type == 7 OR $clubEvent->evnt_type == 8)
							<div class="cal-event calendar-public-marketing">
						@elseif ($clubEvent->getPlace->id == 2)
							<div class="cal-event calendar-public-event-bc-club">
						@elseif ($clubEvent->getPlace->id == 3)
							<div class="cal-event calendar-public-event-bc-cafe">
						@else
							{{-- DEFAULT --}}
							<div class="cal-event dark-grey">
						@endif
					@endif

						@include("partials.event-marker", $clubEvent)
						<a href="{{ URL::route('event.show', $clubEvent->id) }}"> 
							{{{ $clubEvent->evnt_title }}}
						</a>
					</div>

				@endif

		</div>
	@endif
	
@endforeach 

@foreach($tasks as $task)
				
	{{-- sucht die Events zu den passenden Tagen --}}
	@if($task->schdl_due_date === date("Y-m-d", strtotime($i - $date['startDay']." day", $date['startStamp'])))

		<div class="word-break">
			@if(Session::has('userId'))	
				{{-- show private events only if user is logged in --}}
				<div class="cal-event calendar-task">
					<i class="fa fa-tasks"></i>
					<a href="{{ Request::getBasePath() }}/task/id/{{ $task->id }}"> 
						{{{ $task->schdl_title }}}
					</a>
				</div>
			@endif
		</div>
	@endif
	
@endforeach 