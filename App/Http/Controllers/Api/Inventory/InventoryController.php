<?php

namespace App\Http\Controllers\Api\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInventoryRequest; 

class InventoryController extends Controller
{
    // GET all inventory
    public function index()
    {
        $data = Inventory::with([
            'lokasi:id,nama_lokasi',
            'divisi:id,nama',
        ])
        ->orderBy('id', 'desc')
        ->get();

        return ApiFormatter::success($data, 'Inventory fetched');
    }


    public function store(StoreInventoryRequest $request)
    {
        $inventory = Inventory::create($request->validated());
        return ApiFormatter::success($inventory, 'Inventory created', 201);
    }



    // GET detail by ID
    public function show($id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return ApiFormatter::error('Inventory not found', 404);
        }

        return ApiFormatter::success($inventory, 'Inventory found');
    }

    // PUT update
    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return ApiFormatter::error('Inventory not found', 404);
        }

        $payload = $request->validate([
            'company_id' => 'required|integer',
            'lokasi_id' => 'nullable|integer',
            'kode_barang' => "required|string|unique:inventories,kode_barang,$id,id",
            'nama_barang' => 'required|string',
            'stok' => 'required|integer',
            'satuan' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);


        $inventory->update($payload);

        return ApiFormatter::success($inventory, 'Inventory updated');
    }

    // DELETE inventory
    public function destroy($id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return ApiFormatter::error('Inventory not found', 404);
        }

        $inventory->delete();

        return ApiFormatter::success(null, 'Inventory deleted', 204);
    }
}
