<?php

namespace App\Filters;

use App\Comment;
use App\User;
use App\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TicketFilter extends \App\Filters\QueryFilter
{


    public function manager($manager)
    {
        if ($manager == 'none') {
            return $this->builder;
        }
        if ($manager == -1) {
            return $this->builder->where('manager_id', 0);
        }
        return $this->builder->where('manager_id', $manager);
    }

    public function status($status)
    {
        if ($status == 'none') {
            return $this->builder;
        }
        return $this->builder->where('status', $status);
    }

    public function answers($answers)
    {
        $tickets = Ticket::all();
        $withComments = [];
        $withoutComments = [];
        $flag = 0;

        foreach ($tickets as $ticket) {
            if (sizeof($ticket->comments) == 0) {
                $withoutComments[] = $ticket->id;
            } else {
                foreach ($ticket->comments as $comment) {
                    foreach (User::where('is_admin', 1)->get() as $admin) {
                        if ($comment->user_id == $admin->id) {
                            $withComments[] = $ticket->id;
                            $flag = 1;
                            break;
                        }
                    }
                    if ($flag == 1) {
                        break;
                    }
                }
            }
            if ($answers == 'yes') {
                return $this->builder->whereIn('id', $withComments);
            } elseif ($answers == 'no') {
                return $this->builder->whereIn('id', $withoutComments);
            }
        }
    }
}
