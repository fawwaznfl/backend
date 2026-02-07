<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class CompanyController extends Controller
{
    public function index()
    {
        try {
            $pegawai = Auth::guard('sanctum')->user();

            // 🧑‍💼 superadmin => semua company
            if ($pegawai->dashboard_type === 'superadmin') {
                $companies = Company::all();
            }
            // 👨‍💻 admin => hanya company miliknya
            elseif ($pegawai->dashboard_type === 'admin') {
                $companies = Company::where('id', $pegawai->company_id)->get();
            }
            // 👷 pegawai => tidak boleh akses
            else {
                return ApiFormatter::error('Unauthorized - Pegawai cannot access companies', 403);
            }

            return ApiFormatter::success($companies, 'Success get companies');
        } catch (Exception $e) {
            return ApiFormatter::error('Failed to get companies', 500, $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $pegawai = Auth::guard('sanctum')->user();

            // 🚫 hanya superadmin yang bisa menambah perusahaan
            if ($pegawai->dashboard_type !== 'superadmin') {
                return ApiFormatter::error('Access denied - only superadmin can create company', 403);
            }

            $request->validate([
                'name' => 'required|string|max:255|unique:companies,name',
                'alamat' => 'nullable|string',
                'telepon' => 'nullable|string|max:20',
                'email' => 'nullable|email|unique:companies,email',
                'website' => 'nullable|string',
            ]);

            $company = Company::create($request->all());

            return ApiFormatter::success($company, 'Company created successfully');
        } catch (Exception $e) {
            return ApiFormatter::error('Failed to create company', 500, $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $pegawai = Auth::guard('sanctum')->user();
            $company = Company::findOrFail($id);

            // 👨‍💻 admin hanya boleh lihat perusahaannya sendiri
            if ($pegawai->dashboard_type === 'admin' && $company->id !== $pegawai->company_id) {
                return ApiFormatter::error('Access denied - cannot view another company', 403);
            }

            // 👷 pegawai tidak boleh lihat detail
            if ($pegawai->dashboard_type === 'pegawai') {
                return ApiFormatter::error('Unauthorized - Pegawai cannot view company detail', 403);
            }

            return ApiFormatter::success($company, 'Company detail retrieved');
        } catch (Exception $e) {
            return ApiFormatter::error('Company not found', 404, $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pegawai = Auth::guard('sanctum')->user();
            $company = Company::findOrFail($id);

            // 👨‍💻 admin hanya bisa update company-nya sendiri
            if ($pegawai->dashboard_type === 'admin' && $company->id !== $pegawai->company_id) {
                return ApiFormatter::error('Access denied - cannot update another company', 403);
            }

            // 👷 pegawai gak boleh update sama sekali
            if ($pegawai->dashboard_type === 'pegawai') {
                return ApiFormatter::error('Unauthorized - Pegawai cannot update company', 403);
            }

            $company->update($request->all());
            return ApiFormatter::success($company, 'Company updated successfully');
        } catch (Exception $e) {
            return ApiFormatter::error('Failed to update company', 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pegawai = Auth::guard('sanctum')->user();

            // 🚫 hanya superadmin yang bisa hapus perusahaan
            if ($pegawai->dashboard_type !== 'superadmin') {
                return ApiFormatter::error('Access denied - only superadmin can delete company', 403);
            }

            $company = Company::findOrFail($id);
            $company->delete();

            return ApiFormatter::success(null, 'Company deleted successfully');
        } catch (Exception $e) {
            return ApiFormatter::error('Failed to delete company', 500, $e->getMessage());
        }
    }

    public function publicList()
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        return ApiFormatter::success($companies, 'Public company list');
    }

}
