<?php 

namespace App\Http\Controllers\Api\Performance;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJenisKinerjaRequest;
use App\Http\Requests\UpdateJenisKinerjaRequest;
use App\Models\JenisKinerja;
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisKinerjaController extends Controller
{
    public function index()
    {
        $data = JenisKinerja::orderBy('id', 'desc')->get();
        return ApiFormatter::success($data, 'Daftar Jenis Kinerja');
    }

    public function store(StoreJenisKinerjaRequest $request)
    {
        $data = JenisKinerja::create([
            'company_id' => $request->company_id ?? Auth::user()->company_id,
            'nama' => $request->nama,
            'detail' => $request->detail,
            'bobot_penilaian' => $request->bobot_penilaian,
            'created_by' => Auth::id(),
        ]);

        return ApiFormatter::success($data, 'Jenis Kinerja berhasil ditambahkan');
    }

    public function show($id)
    {
        $data = JenisKinerja::find($id);
        return $data
            ? ApiFormatter::success($data, 'Detail Jenis Kinerja')
            : ApiFormatter::error(null, 'Data tidak ditemukan', 404);
    }

    public function update(UpdateJenisKinerjaRequest $request, $id)
    {
        $data = JenisKinerja::findOrFail($id);
        $data->update(array_merge(
            $request->validated(),
            ['updated_by' => Auth::id()]
        ));

        return ApiFormatter::success($data, 'Jenis Kinerja berhasil diperbarui');
    }

    public function destroy($id)
    {
        $data = JenisKinerja::findOrFail($id);
        $data->delete();

        return ApiFormatter::success(null, 'Jenis Kinerja berhasil dihapus');
    }
}
