<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\ArchiveTicket;
use App\Models\ArchiveTicketAttachment;
use App\Models\TicketNumberCounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * The list of valid request types for the dropdown.
     */
    public const REQUEST_TYPES = [
        'Incident',
        'Service Request',
        'Access Request',
        'Account Creation',
        'Account Deactivation',
        'Password Reset',
        'Hardware Issue',
        'Software Issue',
        'Network Issue',
        'Printer Issue',
        'Email Issue',
        'Installation',
        'Configuration',
        'Maintenance',
        'Upgrade',
        'Troubleshooting',
        'Asset Management',
        'Relocation / Deployment',
        'Data Migration',
        'Backup & Restore',
        'Security',
    ];

    /**
     * Show the dashboard with all active tickets.
     */
    public function index(Request $request)
    {
        // Fetch all active tickets (soft-deleted are automatically excluded)
        $query = Ticket::withCount('attachments')->latest(); if ($request->has('status') && $request->status !== '') { $query->where('status', $request->status); } $tickets = $query->paginate(10)->appends($request->query());

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show a single ticket with full details and attachments.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load('attachments');

        // Calculate requestor statistics
        $requestor = $ticket->requested_by;
        $stats = [
            'today' => \App\Models\Ticket::where('requested_by', $requestor)
                             ->whereDate('created_at', \Carbon\Carbon::today())
                             ->count(),
            'this_week' => \App\Models\Ticket::where('requested_by', $requestor)
                                 ->whereBetween('created_at', [
                                     \Carbon\Carbon::now()->startOfWeek(),
                                     \Carbon\Carbon::now()->endOfWeek()
                                 ])
                                 ->count(),
            'this_month' => \App\Models\Ticket::where('requested_by', $requestor)
                                  ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                                  ->whereYear('created_at', \Carbon\Carbon::now()->year)
                                  ->count(),
        ];

        return view('tickets.show', compact('ticket', 'stats'));
    }

    /**
     * Show the form to create a new ticket.
     */
    public function create()
    {
        $requestTypes = self::REQUEST_TYPES;
        $employees = \App\Models\Employee::orderBy('last_name')->get();

        return view('tickets.create', compact('requestTypes', 'employees'));
    }

    /**
     * Save the new ticket to the database.
     */
    public function store(Request $request)
    {
        // 1. Validate the form inputs
        $validated = $request->validate([
            'request_type'    => 'required|string',
            'request'         => 'required|string',
            'request_details' => 'required|string',
            'affected_system' => 'nullable|string',
            'requested_by'    => 'required|string',
            'position'        => 'nullable|string',
            'branch'          => 'nullable|string',
            'department'      => 'nullable|string',
            'assisted_by'     => 'nullable|string',
            'status'          => 'required|string',
            'remarks'         => 'nullable|string',
            'attachments'     => 'nullable|array',
            'attachments.*'   => 'file|max:25600|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,mp4,avi,mov,wmv,webm,mkv,mp3,wav,ogg,aac,wma,flac',
        ]);

        // 2. Auto-generate sequential ticket number (never reused)
        $validated['ticket_no'] = TicketNumberCounter::getNextTicketNumber();

        // 3. Save the ticket to the database
        $ticket = Ticket::create($validated);

        // 4. Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // 5. Send you back to the dashboard with a success message
        return redirect()->route('tickets.index')->with('success', 'Ticket recorded successfully!');
    }

    /**
     * Show the form to edit an existing ticket.
     */
    public function edit(Ticket $ticket)
    {
        $requestTypes = self::REQUEST_TYPES;
        $employees = \App\Models\Employee::orderBy('last_name')->get();
        $ticket->load('attachments');

        return view('tickets.edit', compact('ticket', 'requestTypes', 'employees'));
    }

    /**
     * Update the ticket in the database.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // 1. Validate the form inputs
        $validated = $request->validate([
            'request_type'    => 'required|string',
            'request'         => 'required|string',
            'request_details' => 'required|string',
            'affected_system' => 'nullable|string',
            'requested_by'    => 'required|string',
            'position'        => 'nullable|string',
            'branch'          => 'nullable|string',
            'department'      => 'nullable|string',
            'assisted_by'     => 'nullable|string',
            'status'          => 'required|string',
            'remarks'         => 'nullable|string',
            'attachments'     => 'nullable|array',
            'attachments.*'   => 'file|max:25600|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,mp4,avi,mov,wmv,webm,mkv,mp3,wav,ogg,aac,wma,flac',
        ]);

        // 2. Update the ticket
        $ticket->update($validated);

        // 3. Handle new file attachments (append, don't replace)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // 4. Handle attachment removals
        if ($request->has('remove_attachments')) {
            $removeIds = $request->input('remove_attachments', []);
            TicketAttachment::whereIn('id', $removeIds)
                ->where('ticket_id', $ticket->id)
                ->delete();
        }

        return redirect()->route('tickets.show', $ticket->ticket_no)->with('success', 'Ticket updated successfully!');
    }

    /**
     * Soft-delete a ticket and copy it to the archive_tickets table for backup.
     */
    public function destroy(Ticket $ticket)
    {
        // 1. Copy the ticket data to the archive table
        $archiveTicket = ArchiveTicket::create([
            'original_ticket_id'  => $ticket->id,
            'ticket_no'           => $ticket->ticket_no,
            'request_type'        => $ticket->request_type,
            'request'             => $ticket->request,
            'request_details'     => $ticket->request_details,
            'affected_system'     => $ticket->affected_system,
            'requested_by'        => $ticket->requested_by,
            'position'            => $ticket->position,
            'branch'              => $ticket->branch,
            'department'          => $ticket->department,
            'assisted_by'         => $ticket->assisted_by,
            'status'              => $ticket->status,
            'remarks'             => $ticket->remarks,
            'archived_by'         => Auth::user() ? Auth::user()->name : 'System',
            'archived_at'         => now(),
            'original_created_at' => $ticket->created_at,
            'original_updated_at' => $ticket->updated_at,
        ]);

        // 2. Copy attachments references to archive (same file paths, no duplication)
        foreach ($ticket->attachments as $attachment) {
            ArchiveTicketAttachment::create([
                'archive_ticket_id' => $archiveTicket->id,
                'file_name'         => $attachment->file_name,
                'file_path'         => $attachment->file_path,
                'file_type'         => $attachment->file_type,
                'file_size'         => $attachment->file_size,
            ]);
        }

        // 3. Soft-delete the ticket
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket archived successfully!');
    }

    /**
     * Show the archived tickets view.
     */
    public function archived()
    {
        $archivedTickets = ArchiveTicket::withCount('attachments')
            ->latest('archived_at')
            ->paginate(10);

        return view('tickets.archived', compact('archivedTickets'));
    }

    /**
     * Restore an archived ticket back to active tickets.
     */
    public function restore($id)
    {
        $archiveTicket = ArchiveTicket::findOrFail($id);

        // 1. Restore the soft-deleted ticket if it still exists
        $ticket = Ticket::withTrashed()
            ->where('id', $archiveTicket->original_ticket_id)
            ->first();

        if ($ticket) {
            $ticket->restore();
            $ticket->update([
                'restored_from_archive' => true,
                'restored_at'           => now(),
            ]);
        } else {
            // If the original was permanently deleted somehow, recreate it
            $ticket = Ticket::create([
                'ticket_no'             => $archiveTicket->ticket_no,
                'request_type'          => $archiveTicket->request_type,
                'request'               => $archiveTicket->request,
                'request_details'       => $archiveTicket->request_details,
                'affected_system'       => $archiveTicket->affected_system,
                'requested_by'          => $archiveTicket->requested_by,
                'position'              => $archiveTicket->position,
                'branch'                => $archiveTicket->branch,
                'department'            => $archiveTicket->department,
                'assisted_by'           => $archiveTicket->assisted_by,
                'status'                => $archiveTicket->status,
                'remarks'               => $archiveTicket->remarks,
                'restored_from_archive' => true,
                'restored_at'           => now(),
            ]);
        }

        // 2. Remove from archive (this is a restore)
        $archiveTicket->attachments()->delete();
        $archiveTicket->delete();

        return redirect()->route('tickets.archived')->with('success', 'Ticket restored successfully!');
    }
}
