<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

class ResetTicketIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:reset-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely resets ticket IDs starting from 1, cascading to attachments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting safe ID reset for tickets...');

        DB::beginTransaction();

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Include soft-deleted tickets to ensure data consistency
            $tickets = DB::table('tickets')->orderBy('id', 'asc')->get();
            $newId = 1;

            foreach ($tickets as $ticket) {
                $oldId = $ticket->id;

                if ($oldId !== $newId) {
                    // Update attachments to point to the new ID
                    DB::table('ticket_attachments')
                        ->where('ticket_id', $oldId)
                        ->update(['ticket_id' => $newId]);

                    // Update the ticket ID
                    DB::table('tickets')
                        ->where('id', $oldId)
                        ->update(['id' => $newId]);
                        
                    $this->line("Updated Ticket ID $oldId -> $newId");
                }

                $newId++;
            }

            // Reset AUTO_INCREMENT to the next available ID
            DB::statement("ALTER TABLE tickets AUTO_INCREMENT = {$newId}");

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

            $this->info('Successfully renumbered all tickets and attachments!');
            $this->info("Next ticket will be created with ID: {$newId}");

        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->error('Failed to reset IDs: ' . $e->getMessage());
        }
    }
}
