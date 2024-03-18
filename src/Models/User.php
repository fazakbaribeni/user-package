<?php


namespace  Fazakbaribeni\UserApiPackage\Models;


class User implements \JsonSerializable
{
    public $id;
    public $name;
    public $job;

    /**
     * A constructor for initializing the id, name, and job properties.
     *
     * @param integer $id
     * @param string $name
     * @param string $job
     */
    public function __construct($id, $name, $job)
    {
        $this->id = $id;
        $this->name = $name;
        $this->job = $job;
    }


    /**
     * Method to serialize the object to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
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
            'name' => $this->name,
            'job' => $this->job,
        ];
    }
}
