<?php


namespace Fazakbaribeni\UserApiPackage\Models;


/***
 * This User Class is used as a DTO
 * It encapsulates data and provides methods to serialize the object to JSON or convert it to an array
 */
class User implements \JsonSerializable
{
    protected $id;
    protected $first_name;
    protected $last_name;
    protected $job;

    /**
     * A constructor for initializing the id, name, and job properties.
     *
     * @param integer $id
     * @param string $first_name
     * @param string $last_name
     * @param string $job
     */
    public function __construct($id, $first_name, $last_name, $job)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->job = $job;
    }


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
