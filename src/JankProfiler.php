<?php

/*
 * (c) Colin DeCarlo <colin@thedecarlos.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace JankProfiler;

use JsonSerializable;

class JankProfiler implements JsonSerializable
{
    protected $wrapped;
    protected $calls;

    public function __construct($classname = null, ...$constructorArgs)
    {
        if ($classname) {
            $this->build($classname, ...$constructorArgs);
        }
    }

    public function build($classname, ...$constructorArgs)
    {
        $this->reset();

        $call = [
            'type' => 'construct',
            'meta' => [
                'classname' => $classname,
                'constructor_args' => $constructorArgs,
            ],
            'memory_before' => memory_get_usage(),
            'start_time' => microtime(true),
        ];

        $obj = new $classname(...$constructorArgs);

        $call['memory_after'] = memory_get_usage();
        $call['end_time'] = microtime(true);

        $this->wrapped = $obj;
        $this->addCall($call);
    }

    protected function addCall($call)
    {
        $this->calls[] = $call;
    }

    public function report($format)
    {
        switch ($format) {
            case 'array':
                return $this->calls;
                break;
            case 'json':
                return $this->jsonSerialize(JSON_PRETTY_PRINT);
                break;
            default:
                return $this->jsonSerialize();
        }
    }

    public function reset()
    {
        $this->calls = [];
        $this->wrapped = null;
    }

    public function jsonSerialize()
    {
        return $this->calls;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function __call($method, $args)
    {
        $call = [
            'type' => 'method call',
            'meta' => [
                'method' => $method,
                'arguments' => $args
            ],
            'start_time' => microtime(true),
            'memory_before' => memory_get_usage()
        ];

        $result = $this->wrapped->$method(...$args);

        $call['end_time'] = microtime(true);
        $call['memory_after'] = memory_get_usage();

        $this->addCall($call);

        return $result;
    }
}
