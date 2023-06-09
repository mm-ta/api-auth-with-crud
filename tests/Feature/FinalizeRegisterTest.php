<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Tests\AuthTestCase as TestCase;

class FinalizeRegisterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_finalize_registration(): void
    {
        // send register request
        $registerResponse = $this->sendRegisterRequest();

        // first response assertion
        $registerResponse->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'message' => self::$mailedMessage
            ]);

        // check database
        $this->assertDatabaseCount(self::TABLE, 1);
        $this->assertDatabaseHas(self::TABLE, [
            'email' => $this->userData['email']
        ]);

        // fetch secret
        $secret = $this->fetchSecret($this->userData['email']);

        // send finalize request
        $response = $this->sendFinalizeAsDefault($secret);

        $response->assertStatus(SymfonyResponse::HTTP_OK)
            ->assertJson([
                'message' => self::$registeredMessage
            ]);
    }

    // TODO: further failing cases
}
