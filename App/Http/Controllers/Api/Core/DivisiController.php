<?php 

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Http\Requests\StoreDivisiRequest;
use App\Http\Requests\UpdateDivisiRequest;
use ApiFormatter;
use App\Helpers\ApiFormatter as HelpersApiFormatter;
use Illuminate\Support\Facades\Auth;

class DivisiController extends Controller
{
    public function index() { return HelpersApiFormatter::success(Divisi::orderBy('id','desc')->get(), 'Divisis fetched'); }

    public function store(StoreDivisiRequest $request) {
        $payload = $request->validated();
        $payload['created_by'] = auth()->id() ?? null;
        $divisi = Divisi::create($payload);
        return HelpersApiFormatter::success($divisi, 'Divisi created', 201);
    }

    public function show($id) {
        $d = Divisi::find($id);
        if (!$d) return HelpersApiFormatter::error('Not found',404);
        return HelpersApiFormatter::success($d, 'Divisi found');
    }

    public function update(UpdateDivisiRequest $request, $id) {
        $d = Divisi::find($id);
        if (!$d) return HelpersApiFormatter::error('Not found',404);
        $payload = $request->validated();
        $payload['updated_by'] = auth()->id() ?? null;
        $d->update($payload);
        return HelpersApiFormatter::success($d, 'Divisi updated');
    }

    public function destroy($id) {
        $d = Divisi::find($id);
        if (!$d) return HelpersApiFormatter::error('Not found',404);
        $d->delete();
        return HelpersApiFormatter::success(null, 'Divisi deleted', 204);
    }

    public function divisiPegawai()
    {
        $pegawai = Auth::user();

        if (!$pegawai instanceof \App\Models\Pegawai) {
            return HelpersApiFormatter::error('Unauthorized', 403);
        }

        $data = Divisi::where('company_id', $pegawai->company_id)
            ->select('id', 'nama', 'company_id')
            ->get();

        return HelpersApiFormatter::success(
            $data,
            'Daftar divisi pegawai'
        );
    }

}