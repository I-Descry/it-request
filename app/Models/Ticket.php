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
}