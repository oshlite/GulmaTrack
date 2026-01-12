<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Drone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DroneTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Fake storage
        Storage::fake('public');
    }

    /**
     * Test admin can access drone page
     */
    public function test_admin_can_access_drone_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/drone');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.drone');
    }

    /**
     * Test admin can upload drone
     */
    public function test_admin_can_upload_drone()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');
        
        $response = $this->actingAs($admin)->post('/admin/drone/store', [
            'judul' => 'Perencanaan Drone Test',
            'lokasi' => 'Wilayah Test',
            'tanggal_perencanaan' => '2026-01-12',
            'pdf_file' => $file,
        ]);
        
        $response->assertRedirect('/admin/drone');
        $this->assertDatabaseHas('drones', [
            'judul' => 'Perencanaan Drone Test',
            'lokasi' => 'Wilayah Test',
        ]);
    }

    /**
     * Test user can access drone page
     */
    public function test_user_can_access_drone_page()
    {
        $response = $this->get('/drone');
        
        $response->assertStatus(200);
        $response->assertViewIs('pages.drone');
    }

    /**
     * Test user can download drone
     */
    public function test_user_can_download_drone()
    {
        $drone = Drone::factory()->create();
        
        $response = $this->get("/drone/download/{$drone->id}");
        
        // Should attempt to download
        $response->assertStatus(200);
    }

    /**
     * Test admin can delete drone
     */
    public function test_admin_can_delete_drone()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $drone = Drone::factory()->create();
        
        $response = $this->actingAs($admin)->delete("/admin/drone/{$drone->id}");
        
        $response->assertRedirect('/admin/drone');
        $this->assertDatabaseMissing('drones', ['id' => $drone->id]);
    }

    /**
     * Test guest cannot access admin drone page
     */
    public function test_guest_cannot_access_admin_drone_page()
    {
        $response = $this->get('/admin/drone');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test validation on drone upload
     */
    public function test_drone_upload_validation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post('/admin/drone/store', [
            'judul' => '',
            'lokasi' => '',
            'tanggal_perencanaan' => '',
            'pdf_file' => '',
        ]);
        
        $response->assertSessionHasErrors(['judul', 'lokasi', 'tanggal_perencanaan', 'pdf_file']);
    }
}
