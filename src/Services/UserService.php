<?php

namespace Fazakbaribeni\UserApiPackage\Services;


use Fazakbaribeni\UserApiPackage\Contracts\UserRepositoryInterface;
use Fazakbaribeni\UserApiPackage\Models\User;
use GuzzleHttp\Client;
use Fazakbaribeni\UserApiPackage\Exceptions\UserApiException;
use Fazakbaribeni\UserApiPackage\Exceptions\UserNotFoundException;
use Fazakbaribeni\UserApiPackage\Exceptions\PageNotFoundException;
use GuzzleHttp\Exception\RequestException;


class UserService implements UserRepositoryInterface
{
    public $client;

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
    public function findByID($id): ?User
    {
        try {
            $response = $this->client->request('GET', "users/{$id}");


            // Check if the user was found.
            if ($response->getStatusCode() == 404) {
                throw new UserNotFoundException("User with ID {$id} not found.");
            }

            $data = json_decode($response->getBody()->getContents(), true)['data'];

            return new User($data['id'], $data['name'], $data['job']);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // Wrap the Guzzle exception in a domain-specific exception.
            throw new UserApiException("Failed to retrieve user: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Find all users based on the given page number.
     *
     * @param int $page The page number for pagination.
     * @return array The array of User objects.
     */
    public function findAll($page): array
    {

        try {


            $response = $this->client->request('GET', "users?page={$page}");

            // Check if the page was found otherwise throw an exception.
            if ($response->getStatusCode() == 404) {
                throw new PageNotFoundException("Page {$page}  not found.");
            }


            $data = json_decode($response->getBody()->getContents(), true)['data'];
            $users = [];

            foreach ($data as $userData) {
                $users[] = new User($userData['id'], $userData['name'], '');
            }
            return $users;
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            // Wrap the Guzzle exception in a domain-specific exception.
            throw new UserApiException("Failed to retrieve users by that page ID : " . $e->getMessage(), 0, $e);
        }

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

        try {

            if ($name == '' || $job == '') {
                throw new UserNotFoundException('User or the name and job could not be empty.');
            }
            
            $response = $this->client->request('POST', 'users', [
                'json' => ['name' => $name, 'job' => $job]
            ]);
            

            $data = json_decode($response->getBody()->getContents(), true);

            if (!isset($data['id'])) {
                throw new UserNotFoundException('User could not be created.');
            }

            return new User($data['id'], $name, $job);

        } catch (RequestException $e) {
            // Rethrow the Guzzle exception as your own domain-specific exception
            throw new UserApiException('Failed to create user: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            // Catch any other generic exceptions
            throw new UserApiException('An unexpected error occurred: ' . $e->getMessage(), 0, $e);
        }

    }
}
