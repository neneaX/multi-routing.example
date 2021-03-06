<?php
namespace Example\Common\Infrastructure\Contracts;

interface Jsonable extends \Illuminate\Contracts\Support\Jsonable
{

    /**
     * Convert the object to its JSON representation.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0);

}