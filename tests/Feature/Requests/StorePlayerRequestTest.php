<?php

namespace Tests\Feature\Requests;

use App\Http\Requests\Player\StorePlayerRequest;
use App\Rules\PlayerPositionRule;
use Tests\TestCase;

class StorePlayerRequestTest extends TestCase
{
    public function test_the_request_should_validate_field_name(): void
    {
        $request = new StorePlayerRequest();

        $this->assertArrayHasKey('name', $request->rules());
        $this->assertEquals(['bail', 'required'], $request->rules()['name']);
    }

    public function test_the_request_should_validate_field_position(): void
    {
        $request = new StorePlayerRequest();

        $this->assertArrayHasKey('position', $request->rules());
        $this->assertEquals(['required', new PlayerPositionRule()], $request->rules()['position']);
    }
}
