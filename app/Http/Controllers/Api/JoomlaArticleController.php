<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JoomlaArticleController extends Controller
{
    /**
     * Mengambil daftar artikel dari Joomla.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // 1. Buat parameter query secara ringkas
            $query = array_filter([
                'page' => array_filter([
                    'offset' => $request->integer('page', 1),
                    'limit'  => $request->integer('limit'),
                ]),
                'filter' => array_filter([
                    'category' => $request->integer('category'),
                ]),
            ]);

            // 2. Request ke API Joomla
            $response = Http::timeout(15)
                ->accept('application/vnd.api+json')
                ->withHeaders([
                    'X-Joomla-Token' => config('services.joomla.token'),
                ])
                ->get(rtrim(config('services.joomla.url'), '/') . '/content/articles', $query);

            // 3. Handling jika API gagal
            if ($response->failed()) {
                Log::error('Joomla API Error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil artikel dari Joomla.',
                ], $response->status());
            }

            $data = $response->json();
            $articles = collect($data['data'] ?? [])->take(5)->values();

            return response()->json([
                'success' => true,
                'data'    => $articles,
                'meta'    => $data['meta'] ?? null,
                'links'   => $data['links'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Joomla API Exception', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghubungi Joomla.',
            ], 500);
        }
    }

    /**
     * Mengambil satu artikel berdasarkan ID Joomla.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $response = Http::timeout(15)
                ->accept('application/vnd.api+json')
                ->withHeaders([
                    'X-Joomla-Token' => config('services.joomla.token'),
                ])
                ->get(
                    rtrim(config('services.joomla.url'), '/') .
                        '/content/articles/' . $id
                );

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Artikel tidak ditemukan atau Joomla API gagal.',
                    'status' => $response->status(),
                ], $response->status());
            }

            $data = $response->json();

            return response()->json([
                'success' => true,
                'data' => $data['data'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Joomla Article Error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil artikel.',
            ], 500);
        }
    }
}
