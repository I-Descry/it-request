<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveTicket extends Model
{
    protected $fillable = [
        'original_ticket_id',
        'ticket_no',
        'request_type',
        'request_details',
        'affected_system',
        'requested_by',
        'position',
        'branch',
        'assisted_by',
        'status',
        'remarks',
        'archived_by',
        'archived_at',
        'original_created_at',
        'original_updated_at',
    ];

    protected $casts = [
        'archived_at'        => 'datetime',
        'original_created_at' => 'datetime',
        'original_updated_at' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(ArchiveTicketAttachment::class);
    }
}
