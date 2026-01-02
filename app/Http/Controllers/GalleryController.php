<?php

namespace App\Http\Controllers;

use App\Models\GulmaPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display gallery page
     */
    public function index()
    {
        $photos = GulmaPhoto::with('uploader')
            ->orderBy('kategori')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = GulmaPhoto::getStats();

        return view('admin.gallery', compact('photos', 'stats'));
    }

    /**
     * Upload photos for specific category
     */
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'photos' => 'required|array|max:10',
                'photos.*' => 'required|image|mimes:jpeg,jpg,png|max:5120',
                'kategori' => 'required|in:bersih,ringan,sedang,berat',
                'deskripsi' => 'nullable|string|max:1000',
                'set_as_primary' => 'nullable|boolean',
            ], [
                'photos.required' => 'Pilih minimal 1 foto',
                'photos.max' => 'Maksimal 10 foto per upload',
                'photos.*.image' => 'File harus berupa gambar',
                'photos.*.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
                'photos.*.max' => 'Ukuran foto maksimal 5MB',
                'kategori.required' => 'Kategori gulma harus dipilih',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $uploadedPhotos = [];
            $kategori = $request->kategori;
            $setAsPrimary = $request->has('set_as_primary') && $request->set_as_primary;
            
            foreach ($request->file('photos') as $index => $photo) {
                try {
                    $filename = 'gulma_' . $kategori . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('gulma_photos', $filename, 'public');

                    $gulmaPhoto = GulmaPhoto::create([
                        'kategori' => $kategori,
                        'foto_path' => $path,
                        'deskripsi' => $request->deskripsi,
                        'uploaded_by' => auth()->id(),
                        'file_size' => $photo->getSize(),
                        'mime_type' => $photo->getMimeType(),
                        'is_primary' => $setAsPrimary ? 1 : 0,
                    ]);

                    $uploadedPhotos[] = $gulmaPhoto;

                } catch (\Exception $e) {
                    \Log::error('Error uploading photo: ' . $e->getMessage());
                    continue;
                }
            }

            if (count($uploadedPhotos) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload foto. Silakan coba lagi.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengupload ' . count($uploadedPhotos) . ' foto untuk kategori ' . strtoupper($kategori),
                'data' => $uploadedPhotos
            ]);

        } catch (\Exception $e) {
            \Log::error('Gallery upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get photos with filters
     */
    public function getPhotos(Request $request)
    {
        try {
            $query = GulmaPhoto::with('uploader');

            if ($request->has('kategori') && $request->kategori) {
                $query->kategori($request->kategori);
            }

            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 12);
            $photos = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $photos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get photos by category (for map popup)
     */
    public function getByCategory($kategori)
    {
        try {
            // Convert kategori to lowercase for case-insensitive search
            $kategoriLower = strtolower($kategori);
            
            $photos = GulmaPhoto::whereRaw('LOWER(kategori) = ?', [$kategoriLower])
                ->with('uploader')
                ->orderBy('is_primary', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'kategori' => $kategori,
                'count' => $photos->count(),
                'data' => $photos->map(function($photo) {
                    return [
                        'id' => $photo->id,
                        'foto_url' => $photo->foto_url,
                        'deskripsi' => $photo->deskripsi,
                        'is_primary' => $photo->is_primary,
                        'uploaded_by' => $photo->uploader?->name,
                        'uploaded_at' => $photo->created_at->format('d M Y'),
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single photo
     */
    public function show($id)
    {
        try {
            $photo = GulmaPhoto::with('uploader')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $photo->id,
                    'kategori' => $photo->kategori,
                    'foto_url' => $photo->foto_url,
                    'deskripsi' => $photo->deskripsi,
                    'is_primary' => $photo->is_primary,
                    'uploader' => $photo->uploader->name,
                    'file_size' => $photo->file_size_formatted,
                    'uploaded_at' => $photo->created_at->format('d M Y H:i'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Foto tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update photo
     */
    public function update(Request $request, $id)
    {
        try {
            $photo = GulmaPhoto::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'kategori' => 'sometimes|required|in:bersih,ringan,sedang,berat',
                'deskripsi' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $photo->update([
                'kategori' => $request->get('kategori', $photo->kategori),
                'deskripsi' => $request->get('deskripsi', $photo->deskripsi),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data foto berhasil diupdate',
                'data' => $photo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete photo
     */
    public function destroy($id)
    {
        try {
            $photo = GulmaPhoto::findOrFail($id);

            if (Storage::disk('public')->exists($photo->foto_path)) {
                Storage::disk('public')->delete($photo->foto_path);
            }

            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stats
     */
    public function getStats()
    {
        try {
            $stats = GulmaPhoto::getStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Serve image file directly
     */
    public function serveImage($id)
    {
        try {
            $photo = GulmaPhoto::findOrFail($id);

            if (!Storage::disk('public')->exists($photo->foto_path)) {
                return response()->file(public_path('image/roblox.png'));
            }

            $filePath = Storage::disk('public')->path($photo->foto_path);
            
            return response()->file($filePath, [
                'Content-Type' => $photo->mime_type ?? 'image/jpeg',
                'Cache-Control' => 'public, max-age=31536000',
            ]);

        } catch (\Exception $e) {
            return response()->file(public_path('image/roblox.png'));
        }
    }
}