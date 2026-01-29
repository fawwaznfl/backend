<?php

namespace App\Http\Controllers\Api\HR;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DokumenPegawai;
use Illuminate\Support\Facades\Storage;

class DokumenPegawaiController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $query = DokumenPegawai::with('pegawai');

        if ($request->search) {
            $query->whereHas('pegawai', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal_upload', [$request->start_date, $request->end_date]);
        }

        $data = $query->orderBy('tanggal_upload', 'desc')->get()
            ->map(function ($item) {
            $item->file_url = $item->file
                ? asset('storage/dokumen/' . $item->file)
                : null;
            return $item;
        });


        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:pegawais,id',
            'company_id' => 'required|exists:companies,id',
            'nama_dokumen' => 'required',
            'file' => 'required|file|mimes:pdf,jpg,png,jpeg',
            'keterangan' => 'nullable',
        ]);

        // Upload File
        $fileName = time().'-'.$request->file('file')->getClientOriginalName();
        $request->file('file')->storeAs('dokumen', $fileName, 'public');

        $data = DokumenPegawai::create([
            'pegawai_id' => $request->pegawai_id,
            'company_id' => $request->company_id,
            'nama_dokumen' => $request->nama_dokumen,
            'file' => $fileName,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Dokumen berhasil ditambahkan',
            'data' => $data
        ]);
    }

    // SHOW
    public function show($id)
    {
        $data = DokumenPegawai::with('pegawai')->findOrFail($id);

        $data->file_url = $data->file
            ? asset('storage/dokumen/' . $data->file)
            : null;

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }


    // UPDATE
    public function update(Request $request, $id)
    {
        $data = DokumenPegawai::findOrFail($id);

        $request->validate([
            'nama_dokumen' => 'required',
            'keterangan' => 'nullable',
            'file' => 'nullable|file|mimes:pdf,jpg,png,jpeg'
        ]);

        // Jika upload file baru → hapus file lama
        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists('dokumen/'.$data->file)) {
                Storage::disk('public')->delete('dokumen/'.$data->file);
            }

            $fileName = time().'-'.$request->file('file')->getClientOriginalName();
            $request->file('file')->storeAs('dokumen', $fileName, 'public');

            $data->file = $fileName;
        }

        $data->nama_dokumen = $request->nama_dokumen;
        $data->keterangan = $request->keterangan;
        $data->tanggal_upload = $request->tanggal_upload;
        $data->save();

        return response()->json([
            'status' => 200,
            'message' => 'Dokumen berhasil diperbarui',
            'data' => $data
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $data = DokumenPegawai::findOrFail($id);

        // Hapus file
        if (Storage::disk('public')->exists('dokumen/'.$data->file)) {
            Storage::disk('public')->delete('dokumen/'.$data->file);
        }

        $data->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Dokumen berhasil dihapus'
        ]);
    }

    public function byPegawai($pegawaiId)
    {
        $data = DokumenPegawai::where('pegawai_id', $pegawaiId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($d) {
                return [
                    'id'             => $d->id,
                    'nama_dokumen'   => $d->nama_dokumen,
                    'tanggal_upload' => $d->created_at,
                    'catatan'        => $d->catatan,
                    'file_url'       => $d->file
                        ? asset('storage/dokumen/' . $d->file)
                        : null,
                ];
            });

        return ApiFormatter::success($data, 'Data dokumen pegawai');
    }
}
