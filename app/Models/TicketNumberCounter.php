<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketNumberCounter extends Model
{
    protected $table = 'ticket_number_counter';

    protected $fillable = ['next_number'];

    /**
     * Atomically get the next ticket number and increment the counter.
     * This ensures ticket numbers are never reused.
     *
     * @return string The formatted ticket number (e.g., IT-REQ-0000001)
     */
    public static function getNextTicketNumber(): string
    {
        return DB::transaction(function () {
            $counter = self::lockForUpdate()->first();

            if (!$counter) {
                $counter = self::create(['next_number' => 1]);
            }

            $number = $counter->next_number;
            $counter->increment('next_number');

            return 'IT-REQ-' . str_pad($number, 7, '0', STR_PAD_LEFT);
        });
    }
}
