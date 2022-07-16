<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ApisTest extends TestCase
{
    /**
     * @see AccountController::index()
     * @test
     *
     * @return void
     */
    public function get_accounts_should_returns_a_successful_response()
    {
        $accessToken = $this->getAccessToken();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/accounts');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data',
            'data' => [
                '*' => [
                    'id',
                    'balance',
                    'account_number',
                    'date_opened',
                    'user',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                    'account_cards',
                    'account_cards' => [
                        '*' => [
                            'id',
                            'account_id',
                            'number',
                            'cvv2',
                            'expire_date',
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * @see AccountController::index()
     * @test
     *
     * @return void
     */
    public function get_accounts_should_returns_401_if_access_token_does_not_exist()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->get('api/v1/accounts');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function store_account_should_returns_a_successful_response()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function store_account_should_returns_422_if_account_type_does_not_exist()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'special'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'something'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function store_account_should_returns_422_if_account_type_is_not_normal_or_special()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts');

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function transfer_should_returns_a_successful_response()
    {
        $accessToken = $this->getAccessToken();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $accountsResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/accounts');

        $accounts = $accountsResponse->json();

        $originAccountNumber = $accounts['data'][0]['account_number'];
        $destinationAccountNumber = $accounts['data'][1]['account_number'];
        $amount = "20";

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/transfer' , [
            'origin_account_number' => (string) $originAccountNumber,
            'destination_account_number' => (string) $destinationAccountNumber,
            'amount' => $amount,
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function transfer_should_returns_422_if_body_is()
    {
        $accessToken = $this->getAccessToken();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $accountsResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/accounts');

        $accounts = $accountsResponse->json();

        $originAccountNumber = $accounts['data'][0]['account_number'];
        $destinationAccountNumber = $accounts['data'][1]['account_number'];
        $amount = "20";

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/transfer' , [
            'destination_account_number' => (string) $destinationAccountNumber,
            'amount' => $amount,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/transfer' , [
            'origin_account_number' => (string) $originAccountNumber,
            'amount' => $amount,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/transfer' , [
            'origin_account_number' => (string) $originAccountNumber,
            'destination_account_number' => (string) $destinationAccountNumber,
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function balance_account_should_returns_a_successful_response()
    {
        $accessToken = $this->getAccessToken();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $accountsResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/accounts');

        $accounts = $accountsResponse->json();

        $accountNumber = $accounts['data'][0]['account_number'];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/balance?account_number=' . $accountNumber);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data',
            'data' => [
                'balance'
            ]
        ]);
    }

    /**
     * @see AccountController::store()
     * @test
     *
     * @return void
     */
    public function get_transactions_should_returns_a_successful_response()
    {
        $accessToken = $this->getAccessToken();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/accounts' , [
            'account_type' => 'normal'
        ]);

        $accountsResponse = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/accounts');

        $accounts = $accountsResponse->json();

        $originAccountNumber = $accounts['data'][0]['account_number'];
        $destinationAccountNumber = $accounts['data'][1]['account_number'];
        $amount = "20";

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->post('api/v1/transfer' , [
            'origin_account_number' => (string) $originAccountNumber,
            'destination_account_number' => (string) $destinationAccountNumber,
            'amount' => $amount,
        ]);

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken
        ])->get('api/v1/transactions?account_number=' . $originAccountNumber);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data',
            'data' => [
                '*' => [
                    "origin_account_number" ,
                    "destination_account_number",
                    "date",
                    "type"
                ]
            ]
        ]);
    }
}
