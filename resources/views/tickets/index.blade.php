@extends('layouts.app')

@section('title', 'All Tickets')

@section('content')
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-ticket"> Tickets</i>
                </div>

                <div class="panel-body">
                    @if ($tickets->isEmpty())
                        <p>There are currently no tickets.</p>
                    @else
                        @include('includes.flash')
                        <table class="table">

                            <thead>
                            <tr>
                                <form action="{{ url('admin/tickets') }}" method="get">
                                    <td>
                                        <select name="manager">
                                            <option value="none">Accepted?</option>
                                            <option value="{{\Illuminate\Support\Facades\Auth::user()->id}}">Accepted
                                            </option>
                                            <option value="-1">Not accepted</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="status">
                                            <option value="none">Status</option>
                                            <option value="Closed">Closed</option>
                                            <option value="Open">Opened</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="answers">
                                            <option value="none">Answers</option>
                                            <option value="yes">+</option>
                                            <option value="no">-</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-danger">Accept</button>
                                    </td>
                                </form>
                                <form action="{{ url('admin/tickets/reset') }}" method="get">
                                    <td>
                                        <button type="submit" class="btn btn-danger">Reset</button>
                                    </td>
                                </form>
                            </tr>
                            </thead>

                        </table>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                                <th style="text-align:center" colspan="3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
{{--                                        @foreach ($categories as $category)--}}
{{--                                            @if ($category->id === $ticket->category_id)--}}
{{--                                                {{ $category->name }}--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
                                        {{$ticket->category->name}}
                                    </td>
                                    <td>
                                        <a href="{{ url('tickets/'. $ticket->ticket_id) }}">
                                            #{{ $ticket->ticket_id }} - {{ $ticket->title }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($ticket->status === 'Open')
                                            <span class="label label-success">{{ $ticket->status }}</span>
                                        @else
                                            <span class="label label-danger">{{ $ticket->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->updated_at }}</td>
                                    <td>
                                        @if ($ticket->status === 'Open')
                                            <a href="{{ url('tickets/' . $ticket->ticket_id) }}"
                                               class="btn btn-primary">Comment</a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ticket->status === 'Open')
                                            <form action="{{ url('tickets/' . $ticket->ticket_id . '/close') }}"
                                                  method="POST">
                                                {!! csrf_field() !!}
                                                <button type="submit" class="btn btn-danger">Close</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($ticket->manager_id === 0)
                                            @if ($ticket->status === 'Open')
                                                <form action="{{ url('tickets/' . $ticket->ticket_id . '/accept') }}"
                                                      method="POST">
                                                    {!! csrf_field() !!}
                                                    <button type="submit" class="btn btn-danger">Accept</button>
                                                </form>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $tickets->render() }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
