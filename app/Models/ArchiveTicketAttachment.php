<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveTicketAttachment extends Model
{
    protected $fillable = [
        'archive_ticket_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function archiveTicket()
    {
        return $this->belongsTo(ArchiveTicket::class);
    }
}
