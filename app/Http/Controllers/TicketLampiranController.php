<?php

namespace App\Http\Controllers;

use App\Models\TicketLampiran;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TicketLampiranController extends Controller
{
    public function show(TicketLampiran $lampiran): Response
    {
        $user = auth()->user();
        $role = $user?->role?->nama_role;

        $canView = in_array($role, ['Super Admin', 'Admin', 'SPV'], true)
            || (int) $lampiran->uploaded_by === (int) $user?->id
            || (int) $lampiran->ticket?->teknisi_id === (int) $user?->id;

        abort_unless($canView, 403);
        abort_unless(Storage::disk('public')->exists($lampiran->path_file), 404);

        return Storage::disk('public')->response(
            $lampiran->path_file,
            $lampiran->nama_file
        );
    }
}
