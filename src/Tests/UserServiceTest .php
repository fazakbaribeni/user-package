<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;
use Fazakbaribeni\UserApiPackage\Services\UserService;
use Fazakbaribeni\UserApiPackage\Models\User;
use Fazakbaribeni\UserApiPackage\Exceptions\UserNotFoundException;
use Fazakbaribeni\UserApiPackage\Exceptions\PageNotFoundException;
use Fazakbaribeni\UserApiPackage\Exceptions\UserApiException;


// Test for finding a user by ID successfully
test('finds a user by ID successfully', function () {
    $mock = new MockHandler([
        new Response(200, [], json_encode(['data' => ['id' => 1, 'name' => 'Farhad Akbari-Beni', 'job' => 'Fisherman']])),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $userService = new UserService();
    $userService->client = $client; // Override the client with the mock

    $user = $userService->findByID(1);

    expect($user)->toBeInstanceOf(User::class)
        ->id->toEqual(1)
        ->name->toEqual('Farhad Akbari-Beni');
});

// Test for handling user not found
test('throws an exception if user is not found', function () {
    $mock = new MockHandler([
        new Response(404),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $userService = new UserService();
    $userService->client = $client;

    $userService->findByID(999);
})->throws(UserNotFoundException::class);

// Test for finding all users successfully
test('finds all users successfully', function () {
    $mockResponseData = [
        'data' => [
            ['id' => 1, 'name' => 'John', 'job' => 'Doe'],
            ['id' => 2, 'name' => 'Jane', 'job' => 'Doe']
        ]
    ];

    $mock = new MockHandler([
        new Response(200, [], json_encode($mockResponseData)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $userService = new UserService();
    $userService->client = $client;

    $users = $userService->findAll(1);

    expect($users)->toBeArray()->toHaveCount(2);
    expect($users[0])->toBeInstanceOf(User::class)
        ->id->toEqual(1);
});

// Test for handling page not found when finding all users
test('throws an exception if page is not found while finding all users', function () {
    $mock = new MockHandler([
        new Response(404),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $userService = new UserService();
    $userService->client = $client;

    $userService->findAll(999);
})->throws(PageNotFoundException::class);

// Test for successful user creation
test('creates a user successfully', function () {
    $mockResponseData = [
        'id' => 123,
        'name' => 'New User',
        'job' => 'Developer'
    ];

    $mock = new MockHandler([
        new Response(201, [], json_encode($mockResponseData)),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $userService = new UserService();
    $userService->client = $client;

    $user = $userService->create('New User', 'Developer');

    expect($user)->toBeInstanceOf(User::class)
        ->id->toEqual(123);
});

// You can add more tests following this pattern.
