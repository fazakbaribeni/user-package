<?php

namespace Fazakbaribeni\UserApiPackage\Services;


use Fazakbaribeni\UserApiPackage\Contracts\UserRepositoryInterface;
use Fazakbaribeni\UserApiPackage\Models\User;
use GuzzleHttp\Client;
use Fazakbaribeni\UserApiPackage\Exceptions\UserApiException;
use Fazakbaribeni\UserApiPackage\Exceptions\UserNotFoundException;
use Fazakbaribeni\UserApiPackage\Exceptions\PageNotFoundException;
use GuzzleHttp\Exception\RequestException;
use function PHPUnit\Framework\exactly;


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
    public function findByID($id): ?User
    {
        try {
            $response = $this->client->request('GET', "users/{$id}");


            // Check if the user was found.
            if ($response->getStatusCode() == 404) {
                throw new UserNotFoundException("User with ID {$id} not found.");
            }

            $data = json_decode($response->getBody()->getContents(), true)['data'];

            return new User($data['id'], $data['first_name'] ?? '', $data['last_name'] ?? '',  $data['job']?? '');

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
                $users[] = new User($userData['id'], $userData['first_name'], $userData['last_name'], '');
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
            // Check if the name or job is empty
            if ($name == '' || $job == '') {
                throw new UserNotFoundException('User or the name and job could not be empty.');
            }

            // Make the POST request to create a new user
            $response = $this->client->request('POST', 'users', [
                'json' => ['name' => $name, 'job' => $job]
            ]);

            // Decode the response body
            $data = json_decode($response->getBody()->getContents(), true);

            // Check if the response includes an id, indicating user creation was successful
            if (!isset($data['id'])) {
                throw new UserNotFoundException('User could not be created.');
            }

            // Assuming the API returns the full name in the 'name' field, split it for the User model
            $nameArray = explode(" ", $name, 2); // Split the name into at most 2 parts
            $firstName = $nameArray[0];
            $lastName = $nameArray[1] ?? ''; // Default to empty string if no last name

            // Create and return the new User instance
            return new User($data['id'], $firstName, $lastName, $job);

        } catch (RequestException $e) {
            // Rethrow the Guzzle exception as your own domain-specific exception
            throw new UserApiException('Failed to create user: ' . $e->getMessage(), 0, $e);
        } catch (\Exception $e) {
            // Catch any other generic exceptions
            throw new UserApiException('An unexpected error occurred: ' . $e->getMessage(), 0, $e);
        }
    }
}
