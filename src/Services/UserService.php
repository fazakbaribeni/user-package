<?php

namespace Fazakbaribeni\UserApiPackage\Services;


use Fazakbaribeni\UserApiPackage\Contracts\UserRepositoryInterface;
use Fazakbaribeni\UserApiPackage\Models\User;
use GuzzleHttp\Client;

class UserService implements UserRepositoryInterface
{
    protected $client;

    /**
     * Create a new HTTP Request instance to pass to the other method calls
     */

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://reqres.in/api/']);
    }

    /**
     * Find a user by their ID.
     *
     * @param int $id The ID of the user to find.
     * @return User|null
     */
    public function find($id): ?User
    {
        $response = $this->client->request('GET', "users/{$id}");
        $data = json_decode($response->getBody()->getContents(), true)['data'];

        return new User($data['id'], $data['first_name'], ''); // Assuming 'job' is not available
    }

    /**
     * Find all users based on the given page number.
     *
     * @param int $page The page number for pagination.
     * @return array The array of User objects.
     */
    public function findAll($page): array
    {
        $response = $this->client->request('GET', "users?page={$page}");
        $data = json_decode($response->getBody()->getContents(), true)['data'];
        $users = [];

        foreach ($data as $userData) {
            $users[] = new User($userData['id'], $userData['first_name'], '');
        }

        return $users;
    }

    /**
     * Create a new user with the given name and job.
     *
     * @param mixed $name The name of the user.
     * @param mixed $job The job of the user.
     * @return User The newly created User object.
     */
    public function create($name, $job): User
    {
        $response = $this->client->request('POST', 'users', [
            'json' => ['name' => $name, 'job' => $job]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);

        return new User($data['id'], $name, $job);
    }
}
