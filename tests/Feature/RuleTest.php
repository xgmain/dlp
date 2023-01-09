<?php
use App\Models\Policy;
use App\Models\Rule;

it('list all rules', function () {

    Policy::factory(5)->create();
    Rule::factory(20)->create();

    $response = $this->getJson('/api/rule');
    $response->assertStatus(200)->assertJson([
        'count' => 20,
        'message' => 'data loss found',
    ]);
});
