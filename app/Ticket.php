<?php

namespace App;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var  array
	 */
    protected $fillable = [
    	'user_id', 'category_id', 'ticket_id', 'title', 'priority', 'file', 'extension', 'message', 'status', 'manager_id'
    ];

    /**
     * A ticket belongs to a user
     */
	public function user()
    {
    	return $this->belongsTo(User::class);
   }

    /**
     * A ticket can have many comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * A ticket belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filter)
    {
        return $filter->apply($builder);
    }
}
