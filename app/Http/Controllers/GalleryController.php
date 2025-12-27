<?php

namespace App\Http\Controllers;

use App\Models\GulmaPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image; // Optional: untuk resize/optimize

class GalleryController extends Controller
{
    /**
     * Constructor - require auth & admin
     */
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
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = GulmaPhoto::getStats();

        return view('admin.gallery', compact('photos', 'stats'));
    }

    /**
     * Upload photos (multiple)
     */
    public function upload(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'photos' => 'required|array|max:10',
                'photos.*' => 'required|image|mimes:jpeg,jpg,png|max:5120', // Max 5MB per file
                'wilayah' => 'required|string',
                'lokasi' => 'required|string|max:50',
                'status' => 'required|in:bersih,ringan,sedang,berat',
                'tanggal' => 'required|date',
                'deskripsi' => 'nullable|string|max:1000',
            ], [
                'photos.required' => 'Pilih minimal 1 foto',
                'photos.max' => 'Maksimal 10 foto per upload',
                'photos.*.image' => 'File harus berupa gambar',
                'photos.*.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
                'photos.*.max' => 'Ukuran foto maksimal 5MB',
                'wilayah.required' => 'Wilayah harus dipilih',
                'lokasi.required' => 'Kode lokasi harus diisi',
                'status.required' => 'Status gulma harus dipilih',
                'tanggal.required' => 'Tanggal pengambilan foto harus diisi',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $uploadedPhotos = [];
            
            foreach ($request->file('photos') as $photo) {
                try {
                    // Generate unique filename
                    $filename = 'gulma_' . $request->wilayah . '_' . $request->lokasi . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    
                    // Store in storage/app/public/gulma_photos
                    $path = $photo->storeAs('gulma_photos', $filename, 'public');

                    // Optional: Optimize/Resize image untuk hemat storage
                    // $this->optimizeImage(storage_path('app/public/' . $path));

                    // Save to database
                    $gulmaPhoto = GulmaPhoto::create([
                        'wilayah_id' => $request->wilayah,
                        'lokasi' => strtoupper(trim($request->lokasi)),
                        'foto_path' => $path,
                        'status_gulma' => $request->status,
                        'tanggal_foto' => $request->tanggal,
                        'deskripsi' => $request->deskripsi,
                        'uploaded_by' => auth()->id(),
                        'file_size' => $photo->getSize(),
                        'mime_type' => $photo->getMimeType(),
                    ]);

                    $uploadedPhotos[] = $gulmaPhoto;

                } catch (\Exception $e) {
                    // Log error but continue with other files
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
                'message' => 'Berhasil mengupload ' . count($uploadedPhotos) . ' foto',
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
     * Get photos (API with filters)
     */
    public function getPhotos(Request $request)
    {
        try {
            $query = GulmaPhoto::with('uploader');

            // Apply filters
            if ($request->has('wilayah') && $request->wilayah) {
                $query->wilayah($request->wilayah);
            }

            if ($request->has('status') && $request->status) {
                $query->status($request->status);
            }

            if ($request->has('search') && $request->search) {
                $query->search($request->search);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
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
     * Get single photo detail
     */
    public function show($id)
    {
        try {
            $photo = GulmaPhoto::with('uploader')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $photo->id,
                    'wilayah_id' => $photo->wilayah_id,
                    'lokasi' => $photo->lokasi,
                    'foto_url' => $photo->foto_url,
                    'status_gulma' => $photo->status_gulma,
                    'tanggal_foto' => $photo->tanggal_foto->format('d M Y'),
                    'deskripsi' => $photo->deskripsi,
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
     * Update photo metadata
     */
    public function update(Request $request, $id)
    {
        try {
            $photo = GulmaPhoto::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'wilayah' => 'sometimes|required|string',
                'lokasi' => 'sometimes|required|string|max:50',
                'status' => 'sometimes|required|in:bersih,ringan,sedang,berat',
                'tanggal' => 'sometimes|required|date',
                'deskripsi' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $photo->update([
                'wilayah_id' => $request->get('wilayah', $photo->wilayah_id),
                'lokasi' => strtoupper(trim($request->get('lokasi', $photo->lokasi))),
                'status_gulma' => $request->get('status', $photo->status_gulma),
                'tanggal_foto' => $request->get('tanggal', $photo->tanggal_foto),
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

            // Delete file from storage
            if (Storage::disk('public')->exists($photo->foto_path)) {
                Storage::disk('public')->delete($photo->foto_path);
            }

            // Soft delete from database
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
     * Get photos by location (for map popup)
     */
    public function getByLocation($wilayah, $lokasi)
    {
        try {
            $photos = GulmaPhoto::getByLokasi($wilayah, $lokasi);

            return response()->json([
                'success' => true,
                'count' => $photos->count(),
                'data' => $photos->map(function($photo) {
                    return [
                        'id' => $photo->id,
                        'foto_url' => $photo->foto_url,
                        'status_gulma' => $photo->status_gulma,
                        'tanggal_foto' => $photo->tanggal_foto->format('d M Y'),
                        'deskripsi' => $photo->deskripsi,
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
     * Get gallery statistics
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
     * Optional: Optimize image untuk hemat storage
     */
    private function optimizeImage($path)
    {
        try {
            $img = Image::make($path);
            
            // Resize jika terlalu besar (max width 1920px)
            if ($img->width() > 1920) {
                $img->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Compress quality
            $img->save($path, 85);
            
        } catch (\Exception $e) {
            \Log::error('Image optimization error: ' . $e->getMessage());
        }
    }
}