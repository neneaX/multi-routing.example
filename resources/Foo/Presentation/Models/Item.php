<?php
namespace Example\Foo\Presentation\Models;

use Example\Common\Infrastructure\Contracts\Jsonable;

class Item implements Jsonable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * FooBar constructor.
     *
     * @param $name
     * @param $quantity
     * @param $code
     * @param $enabled
     */
    public function __construct($name, $quantity = 1, $code = '', $enabled = true)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->code = $quantity;
        $this->enabled = (bool)$enabled;
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'enabled' => $this->enabled,
        ];
    }

    /**
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    public function __toString()
    {
        return $this->name . ' | ' . $this->quantity . ' | ' . $this->code . ' | ' . $this->enabled;
    }
}