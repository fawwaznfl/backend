<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\FaceEmbedding;

class FaceController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|exists:pegawais,id',
            'files' => 'required|array|size:3',
            'files.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $files = $request->file('files');
            
            // ✅ PERBAIKAN: asMultipart() + timeout 120 detik
            $response = Http::timeout(120)
                ->asMultipart()
                ->attach('files', file_get_contents($files[0]->getRealPath()), 'face1.jpg')
                ->attach('files', file_get_contents($files[1]->getRealPath()), 'face2.jpg')
                ->attach('files', file_get_contents($files[2]->getRealPath()), 'face3.jpg')
                ->post(env('FACE_SERVICE_URL') . '/register', [
                    'pegawai_id' => $request->pegawai_id
                ]);

            \Log::info('Face service response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Face service gagal',
                    'error' => $response->body()
                ], 500);
            }

            return response()->json([
                'message' => 'Wajah berhasil diregistrasi',
                'data' => $response->json()
            ], 200);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error('Connection error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Tidak dapat terhubung ke Face Service',
                'error' => $e->getMessage()
            ], 500);
            
        } catch (\Exception $e) {
            \Log::error('Register face error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');

            $response = Http::timeout(120)
                ->attach('file', file_get_contents($file->getRealPath()), 'face.jpg')
                ->post(env('FACE_SERVICE_URL') . '/verify');

            \Log::info('Face verify response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Face service gagal',
                    'error' => $response->body()
                ], 500);
            }

            return response()->json($response->json(), 200);

        } catch (\Exception $e) {
            \Log::error('Verify face error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function check($pegawai_id)
    {
        try {
            $response = Http::timeout(30)
                ->get(env('FACE_SERVICE_URL') . "/check/{$pegawai_id}");

            if (!$response->successful()) {
                return response()->json([
                    'message' => 'Face service gagal',
                    'error' => $response->body()
                ], 500);
            }

            return response()->json($response->json(), 200);

        } catch (\Exception $e) {
            \Log::error('Check face error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

        public function verifyViaBackend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pegawai_id' => 'required|exists:pegawais,id',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');

            // Kirim ke Face Service
            $response = Http::timeout(120)
                ->attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    'face.jpg'
                )
                ->post(env('FACE_SERVICE_URL') . '/verify');

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face service error'
                ], 500);
            }

            $result = $response->json();

            // ❗ Validasi pegawai
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Wajah tidak dikenali'
                ]);
            }

            if ($result['pegawai_id'] != $request->pegawai_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wajah bukan milik pegawai ini'
                ]);
            }

            return response()->json([
                'success' => true,
                'pegawai_id' => $result['pegawai_id'],
                'score' => $result['score']
            ]);

        } catch (\Exception $e) {
            \Log::error('Verify via backend error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }

}