<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ManipulateImageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_see_gallery()
    {
        $image = create(Image::class);

        $response = $this->getJson('gallery')->json();

        $this->assertCount(1, $response);
        $this->assertEquals($image->filename, $response[0]['filename']);
    }

    /** @test */
    public function guests_may_not_upload_images()
    {
        $this->post('/upload')
            ->assertRedirect('login');
    }

    /** @test */
    public function au_authenticated_user_can_upload_images()
    {
        $this->signIn();

        Storage::fake('public');

        Storage::disk('public')->makeDirectory('thumbs');

        $this->post('/upload', [
                'images' => [$file = UploadedFile::fake()->image('image.jpg')]
            ]);

        Storage::disk('public')->assertExists($file->hashName());
        Storage::disk('public')->assertExists('thumbs/' . $file->hashName());

        $this->assertDatabaseHas('images', [
            'filename' => $file->hashName()
        ]);
    }

    /** @test */
    public function only_images_can_be_uploaded()
    {
        $this->signIn()
            ->post('/upload', [
                'images' => [UploadedFile::fake()->create('anyfile.txt', 1)]
            ])
            ->assertSessionHasErrors('images.0');
    }
}