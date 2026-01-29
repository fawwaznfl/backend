<?php

namespace App\Http\Controllers\Api\Core;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;


class LokasiController extends Controller
{
    /**
     * GET /lokasi
     */
    public function index(Request $request)
    {
        $companyId = $request->query('company_id');

        $query = Lokasi::orderBy('id', 'desc');

        // Jika ada filter company_id → admin mode
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        return ApiFormatter::success($query->get(), 'Lokasi fetched');
    }


    /**
     * POST /lokasi
     */
    public function store(StoreLokasiRequest $request)
    {
        $payload = $request->validated();
        $lokasi = Lokasi::create($payload);

        return ApiFormatter::success($lokasi, 'Lokasi created', 201);
    }

    /**
     * GET /lokasi/{id}
     */
    public function show($id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return ApiFormatter::error('Lokasi not found', 404);
        }

        return ApiFormatter::success($lokasi, 'Lokasi found');
    }

    /**
     * PUT /lokasi/{id}
     */
    public function update(UpdateLokasiRequest $request, $id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return ApiFormatter::error('Lokasi not found', 404);
        }

        $payload = $request->validated();
        $lokasi->update($payload);

        return ApiFormatter::success($lokasi, 'Lokasi updated');
    }

    /**
     * DELETE /lokasi/{id}
     */
    public function destroy($id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return ApiFormatter::error('Lokasi not found', 404);
        }

        $lokasi->delete();

        return ApiFormatter::success(null, 'Lokasi deleted', 204);
    }
}
