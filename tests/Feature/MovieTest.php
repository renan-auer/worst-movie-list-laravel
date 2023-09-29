<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Http\Services\StarterDBService;

class MovieTeste extends TestCase
{

    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp(); 

        $this->migrateFreshUsing(); 

        StarterDBService::populateWithData();
    }

    public function test_the_endpoint_maxMinWinIntervalForProducers_return_status_200()
    {
        $response = $this->get('api/movies/max-min-win-interval-for-producers');
        
        $response->assertStatus(200);
    }

    public function test_the_endpoint_maxMinWinIntervalForProducers_return_valid_data()
    {
        $response = $this->get('api/movies/max-min-win-interval-for-producers')->json();

        $this->assertArrayHasKey('min', $response);
        $this->assertArrayHasKey('max', $response);
    }
}
