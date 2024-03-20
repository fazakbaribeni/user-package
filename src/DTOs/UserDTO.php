<?php


namespace Fazakbaribeni\UserApiPackage\DTOs;


/***
 * This User Class is used as a DTO
 * It encapsulates data and provides methods to serialize the object to JSON or convert it to an array
 */
class UserDTO implements \JsonSerializable
{


    /**
     * A constructor for initializing the id, name, and job properties.
     *
     * @param integer $id
     * @param string $first_name
     * @param string $last_name
     * @param string $job
     */
    public function __construct(
        private int $id,
        private string $first_name,
        private string $last_name,
        private string $job
    )
    {}


    /**
     * Method to serialize the object to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job' => $this->job,
        ];
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'job' => $this->job,
        ];
    }
}
