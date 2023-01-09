<?php
use App\Models\Policy;

it('list all policies', function() {

    Policy::factory(5)->create();

    $response = $this->getJson('/api/policy');
    $response->assertStatus(200)->assertJson([
        'count' => 5,
        'message' => 'list all policies',
    ]);
});

it('get single policy', function() {
    
    $post = Policy::factory()->create();

    $response = $this->getJson("/api/policy/{$post->id}");
    $response->assertStatus(200)->assertJson([
        'data' => [
            'id' => $post->id,
            'client_id' => $post->client_id,
            'name' => $post->name,
        ]
    ]);
});

it('store a policy', function() {

    $attribute = [
        'name' => 'wildcard policy',
        'client_id' => 2,
    ];

    $response = $this->postJson('/api/policy', $attribute);

    $response->assertStatus(201)->assertJson([
        'data' => $attribute
    ]);
});

it('update a policy', function() {

    $put = Policy::factory()->create();

    $attribute = [
        'name' =>  $put->name,
        'client_id' => $put->client_id,   
    ];

    $response = $this->putJson("/api/policy/{$put->id}", $attribute);
    
    $response->assertStatus(200)->assertJson([
        'data' => $attribute
    ]);
});
