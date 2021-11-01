<?php

namespace App\Filters;

use App\Comment;
use App\Models\User;
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

        foreach ($tickets as $ticket) {
            if (sizeof($ticket->comments) == 0) {
                $withoutComments[] = $ticket->id;
            } else {
                if ($ticket->comments->user == ::where('isAdmin', 1)) {
                    $withComments[] = $ticket->id;
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
