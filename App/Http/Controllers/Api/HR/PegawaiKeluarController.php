<?php

namespace App\Http\Controllers\Api\HR;

use App\Http\Controllers\Controller;
use App\Models\PegawaiKeluar;
use App\Http\Requests\StorePegawaiKeluarRequest;
use App\Http\Requests\UpdatePegawaiKeluarRequest;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PegawaiKeluarController extends Controller 
{
    public function index(Request $request)
    {
        $query = PegawaiKeluar::with(['pegawai:id,name,status,company_id', 'company']);

        // Jika superadmin → jangan pakai CompanyScope
        if ($request->dashboard_type === "superadmin") {
            $query->withoutGlobalScope(\App\Scope\CompanyScope::class);
        }

        // Jika ada filter company_id dari frontend
        if ($request->has('company_id') && $request->company_id !== null) {
            $query->where('company_id', $request->company_id);
        }

        return response()->json([
            "data" => $query->get()->map(function ($item) {
                $item->upload_file = $item->upload_file_url; 
                return $item;
            })
        ]);

    }


    public function store(StorePegawaiKeluarRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        /** File Upload */
        if ($request->hasFile('upload_file')) {
            $validated['upload_file'] = 
                $request->file('upload_file')->store('pegawai_keluar', 'public');
        }

        /** Company handling */
        if ($user->dashboard_type === 'superadmin' && $request->company_id) {
            $validated['company_id'] = $request->company_id;
        } else {
            $validated['company_id'] = $user->company_id;
        }

        $validated['created_by'] = $user->id;
        $validated['status'] = 'pending';
        $validated['note_approver'] = null;

        $data = PegawaiKeluar::create($validated);

        return response()->json([
            'message' => 'Data pegawai keluar berhasil ditambahkan',
            'data' => $data
        ]);
    }


    public function show($id)
    {
        $data = PegawaiKeluar::with(['pegawai', 'company'])->findOrFail($id);

        return response()->json($data);
    }

    public function update(UpdatePegawaiKeluarRequest $request, $id)
    {
        $data = PegawaiKeluar::findOrFail($id);
        $validated = $request->validated();

        /** Update File */
        if ($request->hasFile('upload_file')) {

            // hapus file lama
            if ($data->upload_file && Storage::disk('public')->exists($data->upload_file)) {
                Storage::disk('public')->delete($data->upload_file);
            }

            // simpan baru
            $validated['upload_file'] = 
                $request->file('upload_file')->store('pegawai_keluar', 'public');
        }

        $validated['updated_by'] = Auth::id();

        $data->update($validated);

        return response()->json([
            'message' => 'Data pegawai keluar berhasil diperbarui',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = PegawaiKeluar::findOrFail($id);

        // Hapus file jika ada 
        if ($data->upload_file && Storage::disk('public')->exists($data->upload_file)) {
            Storage::disk('public')->delete($data->upload_file);
        }

        $data->delete();

        return response()->json([
            'message' => 'Data pegawai keluar berhasil dihapus'
        ]);
    }

    public function approve(Request $request, $id)
    {
        $data = PegawaiKeluar::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'note_approver' => 'nullable|string',
        ]);

        // Simpan status + catatan
        $data->status = $validated['status'];
        $data->note_approver = $validated['note_approver'] ?? null;
        $data->disetujui_oleh = Auth::id();
        $data->save();

        // JIKA APPROVED, UBAH STATUS PEGAWAI JADI INACTIVE
        if ($validated['status'] === 'approved') {
            $pegawai = Pegawai::find($data->pegawai_id);
            
            if ($pegawai) {
                $pegawai->status = 'inactive';
                $pegawai->save();
            }
        }

        return response()->json([
            'message' => 'Status berhasil diperbarui',
            'data' => $data
        ]);
    }

    public function reject($id)
    {
        $data = PegawaiKeluar::findOrFail($id);

        $data->update([
            'status' => 'rejected',
            'disetujui_oleh' => Auth::id(),
            'note_approver' => request('note_approver')
        ]);

        return response()->json(['message' => 'Berhasil ditolak']);
    }
}
