@extends('layouts.app')

@section('title', $ticket->title)

@section('content')
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
	        <div class="panel panel-default">
	        	<div class="panel-heading">
	        		#{{ $ticket->ticket_id }} - {{ $ticket->title }}
	        	</div>

	        	<div class="panel-body">
	        		@include('includes.flash')

                    <form action="{{ url('/tickets/'.$ticket->ticket_id.'/edit/') }}" method="GET" class="form">
                        <div>
                            {!! csrf_field() !!}

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-ticket"></i> Edit Ticket
                            </button>
                        </div>
                    </form>

                    @if (!empty($ticket->file))
                    <form action="{{ url('/tickets/'.$ticket->ticket_id.'/downloadfile') }}" method="get">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-ticket"></i> Download file
                        </button>
                    </form>
                    @endif



                    <div class="ticket-info">

	        			<p>{{ $ticket->message }}</p>
		        		<p>Category: {{ $category->name }}</p>
		        		<p>
	        			@if ($ticket->status === 'Open')
    						Status: <span class="label label-success">{{ $ticket->status }}</span>
    					@else
    						Status: <span class="label label-danger">{{ $ticket->status }}</span>
    					@endif
		        		</p>
		        		<p>Created on: {{ $ticket->created_at->diffForHumans() }}</p>
                        <p>Manager: @if ($ticket->manager_id !== 0)
                                        {{\App\User::where('id', $ticket->manager_id)->firstOrFail()->name}}
                                    @elseif ($ticket->manager_id === 0)
                                        Not yet
                                    @endif
                        </p>
	        		</div>

	        		<hr>

	        		<div class="comments">
	        			@foreach ($comments as $comment)
	        				<div class="panel panel-@if($ticket->user->id === $comment->user_id){{"default"}}@else{{"success"}}@endif">
	        					<div class="panel panel-heading">
	        						{{ $comment->user->name }}
	        						<span class="pull-right">{{ $comment->created_at->format('Y-m-d') }}</span>
	        					</div>

	        					<div class="panel panel-body">
	        						{{ $comment->comment }}
	        					</div>
	        				</div>
	        			@endforeach
	        		</div>
                    @if ($ticket->status == 'Open')
	        		    <div class="comment-form">
		        		    <form action="{{ url('comment') }}" method="POST" class="form">
		        			    {!! csrf_field() !!}

		        			    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

		        			    <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }}">
                                    <textarea rows="10" id="comment" class="form-control" name="comment"></textarea>

                                    @if ($errors->has('comment'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('comment') }}</strong>
                                        </span>
                                    @endif
	                            </div>

	                            <div class="form-group">
                                 <button type="submit" class="btn btn-primary">Submit</button>
	                            </div>
		        		    </form>
	        	        </div>
                    @endif
	            </div>
	        </div>
	    </div>
    </div>

@endsection
