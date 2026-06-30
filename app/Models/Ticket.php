<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_no',
        'request_type',
        'request',
        'request_details',
        'affected_system',
        'requested_by',
        'position',
        'branch',
        'department',
        'assisted_by',
        'status',
        'remarks',
        'restored_from_archive',
        'restored_at',
    ];

    protected $casts = [
        'restored_from_archive' => 'boolean',
        'restored_at'           => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ticket_no';
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject')->orderBy('created_at', 'desc');
    }

    protected static function booted()
    {
        static::created(function ($ticket) {
            $ticket->activityLogs()->create([
                'action' => 'created',
                'description' => 'Ticket created',
                'properties' => ['new' => $ticket->getAttributes()]
            ]);
        });

        static::updated(function ($ticket) {
            if ($ticket->isDirty()) {
                $ticket->activityLogs()->create([
                    'action' => 'updated',
                    'description' => 'Ticket updated',
                    'properties' => [
                        'old' => $ticket->getOriginal(),
                        'new' => $ticket->getAttributes(),
                        'dirty' => $ticket->getDirty()
                    ]
                ]);
            }
        });
    }
}