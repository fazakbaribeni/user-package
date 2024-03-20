<?php
namespace Fazakbaribeni\UserApiPackage\Contracts;

use Fazakbaribeni\UserApiPackage\DTOs\UserDTO;

interface UserServiceInterface
{

    /**
     * Find a user by their ID.
     *
     * @param int $id The ID of the user to find
     * @return UserDTO|null The found user, or null if not found
     */
    public function findByID($id): ?UserDTO;

    /**
     * Get all users from the API
     *
     * @param integer $page description
     * @return array
     */
    public function findAll($page): array;

    /**
     * Creates a new user with the given name and job.
     *
     * @param mixed $name The name of the user.
     * @param mixed $job The job of the user.
     * @return UserDTO The newly created user.
     */

    public function create($name, $job): UserDTO;
}
