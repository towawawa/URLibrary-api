<?php

namespace Tests\Feature\Resources;

use App\Http\Resources\HashTagResource;
use App\Models\HashTag;
use App\Models\UrlLibrary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\MissingValue;
use Tests\TestCase;

class HashTagResourceTest extends TestCase
{
    use RefreshDatabase;

    private $id;

    public function setUp(): void
    {
        parent::setUp();
        $this->id = HashTag::factory()
            ->has(UrlLibrary::factory(3))
            ->create()
            ->id;
    }

    public function test_モデルの値が正常にセットされているか()
    {
        $model = HashTag::find($this->id);
        $resource = new HashTagResource($model);
        $resource = $resource->toArray(request());

        $this->assertEquals($resource['id'], $model->id);
        $this->assertEquals($resource['name'], $model->name);
        $this->assertEquals($resource['createdAt'], $model->created_at->format('Y-m-d H:i:s'));
        $this->assertEquals($resource['updatedAt'], optional($model->updated_at)->format('Y-m-d H:i:s'));

        $this->assertInstanceOf(MissingValue::class, $resource['urlLibraries']->resource);
    }

    public function test_whenLoaded()
    {
        $with_loaded_model = HashTag::with('urlLibraries')->find($this->id);
        $resource = new HashTagResource($with_loaded_model);
        $resource = $resource->toArray(request());

        dd($resource);
        $this->assertEquals(3, $resource['urlLibraries']->count());
        $this->assertEquals($resource['urlLibraries'][0]['id'], $with_loaded_model->url_library[0]->id);
        $this->assertEquals($resource['urlLibraries'][1]['id'], $with_loaded_model->url_library[1]->id);
        $this->assertEquals($resource['urlLibraries'][2]['id'], $with_loaded_model->url_library[2]->id);
    }
}
