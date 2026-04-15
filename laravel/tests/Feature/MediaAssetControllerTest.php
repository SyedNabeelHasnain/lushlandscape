<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\MediaAssetController;
use App\Models\MediaAsset;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Tests\TestCase;

class MediaAssetControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::dropIfExists('media_assets');
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('internal_title');
            $table->string('canonical_filename');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('media_type')->default('image');
            $table->string('mime_type')->default('image/jpeg');
            $table->string('extension')->default('jpg');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();
        });
    }

    public function test_media_json_can_return_a_single_asset_by_id(): void
    {
        $asset = MediaAsset::create([
            'internal_title' => 'Hero Image',
            'canonical_filename' => 'hero.jpg',
            'disk' => 'public',
            'path' => 'media/hero.jpg',
            'media_type' => 'image',
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'file_size' => 12345,
        ]);

        $response = app(MediaAssetController::class)->json(Request::create('/admin/media/json', 'GET', [
            'id' => $asset->id,
        ]));

        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($asset->id, $payload['id']);
        $this->assertSame('Hero Image', $payload['internal_title']);
        $this->assertStringContainsString('/storage/media/hero.jpg', $payload['url']);
    }
}
