<?php namespace Helstern\Nomsky\Dispatcher;

class ReflectionMethodDispatcher implements DoubleDispatcher
{
    /**
     * @var \ReflectionMethod
     */
    private $reflection;

    /**
     * @param \ReflectionMethod $reflection
     */
    public function __construct(\ReflectionMethod $reflection)
    {
        $this->reflection = $reflection;
    }

    public function dispatch($object, $argument)
    {
        $invocationResult = $this->reflection->invoke($object, $argument);
        return $invocationResult;
    }
}
