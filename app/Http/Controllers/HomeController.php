<?php

namespace App\Http\Controllers;
use App\Models\Presence;
use App\Models\PresenceDetail;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $eventStats = Presence::withCount('presenceDetails')
            ->orderBy('tgl_kegiatan', 'desc')
            ->get();

        // Ambil daftar tahun unik dari kegiatan
        $years = Presence::selectRaw('YEAR(tgl_kegiatan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        return view('home', compact('eventStats', 'years'));
    }

    public function chartData(Request $request)
    {
        $year = $request->tahun ?? now()->year;

        $data = Presence::withCount('presenceDetails')
            ->whereYear('tgl_kegiatan', $year)
            ->get();

        return response()->json([
            'labels' => $data->pluck('nama_kegiatan'),
            'counts' => $data->pluck('presence_details_count'),
        ]);
    }

}
