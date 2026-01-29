<?php

namespace App\Http\Controllers\Api\Performance;

use App\Http\Controllers\Controller;
use App\Models\TargetKinerja;
use App\Models\Pegawai;
use Illuminate\Http\Request;

class TargetKinerjaController extends Controller
{
    public function index()
    {
        $data = TargetKinerja::with(['pegawai', 'divisi', 'company'])->latest()->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'pegawai_id' => 'required|exists:pegawais,id',
            'target_pribadi' => 'required|numeric|min:0',
            'jumlah_persen_pribadi' => 'required|numeric|min:0',
            'target_team' => 'nullable|numeric|min:0',
            'jumlah_persen_team' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jackpot' => 'nullable|numeric|min:0',
        ]);

        $pegawai = Pegawai::with('divisi')->findOrFail($request->pegawai_id);

        $data = TargetKinerja::create([
            'company_id' => $request->company_id,
            'pegawai_id' => $pegawai->id,
            'divisi_id' => $pegawai->divisi_id,
            'target_pribadi' => $request->target_pribadi,
            'jumlah_persen_pribadi' => $request->jumlah_persen_pribadi,
            'bonus_pribadi' => ($request->target_pribadi * $request->jumlah_persen_pribadi) / 100,
            'target_team' => $request->target_team ?? $request->target_pribadi,
            'jumlah_persen_team' => $request->jumlah_persen_team ?? 0,
            'bonus_team' => (($request->target_team ?? 0) * ($request->jumlah_persen_team ?? 0)) / 100,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jackpot' => $request->jackpot ?? 0,
        ]);

        return response()->json(['message' => 'Target Kinerja berhasil dibuat', 'data' => $data], 201);
    }

    public function show($id)
    {
        $data = TargetKinerja::with(['pegawai', 'divisi', 'company'])->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = TargetKinerja::findOrFail($id);

        $data->update([
            'target_pribadi' => $request->target_pribadi ?? $data->target_pribadi,
            'jumlah_persen_pribadi' => $request->jumlah_persen_pribadi ?? $data->jumlah_persen_pribadi,
            'bonus_pribadi' => (($request->target_pribadi ?? $data->target_pribadi) * ($request->jumlah_persen_pribadi ?? $data->jumlah_persen_pribadi)) / 100,
            'target_team' => $request->target_team ?? $data->target_team,
            'jumlah_persen_team' => $request->jumlah_persen_team ?? $data->jumlah_persen_team,
            'bonus_team' => (($request->target_team ?? $data->target_team) * ($request->jumlah_persen_team ?? $data->jumlah_persen_team)) / 100,
            'tanggal_mulai' => $request->tanggal_mulai ?? $data->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai ?? $data->tanggal_selesai,
            'jackpot' => $request->jackpot ?? $data->jackpot,
        ]);

        return response()->json(['message' => 'Target Kinerja berhasil diperbarui', 'data' => $data]);
    }

    public function destroy($id)
    {
        $data = TargetKinerja::findOrFail($id);
        $data->delete();

        return response()->json(['message' => 'Target Kinerja berhasil dihapus']);
    }
}
