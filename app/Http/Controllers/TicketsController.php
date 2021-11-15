<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Filters\TicketFilter;
use App\Http\Controllers\Auth\PasswordController;
use App\User;
use App\Ticket;
use App\Category;
use App\Http\Requests;
use App\Mailers\AppMailer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

    /**
     * Display all tickets.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TicketFilter $filter)
    {
        $tickets = Ticket::filter($filter)->paginate(10);
        $categories = Category::all();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    /**
     * Display all tickets by a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function userTickets()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->paginate(10);
        $categories = Category::all();

        return view('tickets.user_tickets', compact('tickets', 'categories'));
    }

    /**
     * Show the form for opening a new ticket.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ticket = Ticket::where('user_id', Auth::user()->id)->latest()->get()->first();
        if (sizeof($ticket) >= 1) {
            $created_at = $ticket->created_at;
            $time = \Carbon\Carbon::now();
            if ($time->diffInSeconds($created_at) >= 86400) {
                $categories = Category::all();
                return view('tickets.create', compact('categories'));
            } else {
                return redirect()->back()->with("status", "You cannot do it now.");
            }
        } else {
            $categories = Category::all();
            return view('tickets.create', compact('categories'));
        }
    }

    /**
     * Store a newly created ticket in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, AppMailer $mailer)
    {
        $this->validate($request, [
            'title'     => 'required',
            'category'  => 'required',
            'priority'  => 'required',
            'message'   => 'required'
        ]);

        if ($request->hasFile('file')) {

            $ticket_id = strtoupper(str_random(10));
            $file = $request->file('file');
            $extension = $request->file('file')->getClientOriginalExtension();
            $user_id = Auth::user()->id;
            $filename = $ticket_id;
            $file->move('D:\OpenServer\domains\ticket\ticket\storage\app\public\img', $filename.'.'.$extension);

            $ticket = new Ticket([
                'title'     => $request->input('title'),
                'user_id'   => Auth::user()->id,
                'ticket_id' => $ticket_id,
                'category_id'  => $request->input('category'),
                'priority'  => $request->input('priority'),
                'message'   => $request->input('message'),
                'status'    => "Open",
                'file'      => $filename,
                'extension' => $extension,
            ]);
        } else {

            $ticket = new Ticket([
                'title' => $request->input('title'),
                'user_id' => Auth::user()->id,
                'ticket_id' => strtoupper(str_random(10)),
                'category_id' => $request->input('category'),
                'priority' => $request->input('priority'),
                'message' => $request->input('message'),
                'status' => "Open",
            ]);
        }

        $ticket->save();

        $mailer->sendTicketInformation(Auth::user(), $ticket);

        return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
    }

    public function edit()
    {
        $categories = Category::all();

        return view('tickets.edit', compact('categories'));
    }

    public function update(Request $request, AppMailer $mailer, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $this->validate($request, [
            'title'     => 'required',
            'category'  => 'required',
            'priority'  => 'required',
            'message'   => 'required'
        ]);

        $ticket->update([
            'title'     => $request->input('title'),
            'category_id'  => $request->input('category'),
            'priority'  => $request->input('priority'),
            'message'   => $request->input('message'),
            'status'    => "Open",
        ]);

        $ticket->save();

        $mailer->sendUpdatedTicketInformation(Auth::user(), $ticket);

        return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been updated.");
    }

    /**
     * Display a specified ticket.
     *
     * @param  int  $ticket_id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $comments = $ticket->comments;

        $category = $ticket->category;

        return view('tickets.show', compact('ticket', 'category', 'comments'));
    }

    /**
     * Close the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close($ticket_id, AppMailer $mailer)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Closed';

        $ticket->save();

        $ticketOwner = $ticket->user;

        $mailer->sendTicketStatusNotification($ticketOwner, $ticket);

        return redirect()->back()->with("status", "The ticket has been closed.");
    }

    public function downloadFile($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        return response()->
                    download(storage_path('app\public\img\\'.$ticket->ticket_id.'.'.$ticket->extension));
    }

    public function accept($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $manager = Auth::user();

        if ($manager->is_admin === 1) {
            $ticket->update([
                'manager_id' => $manager->id
            ]);
            return redirect()->back()->with("status", "Acceptance successfully.");
        } else {
            return redirect()->back()->with("status", "You are not admin.");
        }
    }

    public function reset()
    {
        return redirect('admin/tickets');
    }
}
