<?php namespace Helstern\Nomsky\Dispatcher;

class ReflectiveDoubleDispatcher implements DoubleDispatcher
{
    /** @var string */
    protected $dispatchMethodName;

    /**
     * @param string $dispatchMethodName
     */
    public function __construct($dispatchMethodName)
    {
        $this->dispatchMethodName = $dispatchMethodName;
    }

    /**
     * @param object $object
     * @param object $argument
     * @return mixed
     */
    public function dispatch($object, $argument)
    {
        $this->assertIncorrectDispatchArguments($object, $argument);

        $reflectionMethod = $this->createReflectionMethod($object);
        $invocationResult = $reflectionMethod->invoke($object, $argument);

        return $invocationResult;
    }

    /**
     * @param object $object
     * @throws \Exception
     * @return \ReflectionMethod
     * @throw \ReflectionException
     */
    protected function createReflectionMethod($object)
    {
        try {
            $reflectionMethod = new \ReflectionMethod($object, $this->dispatchMethodName);
            return $reflectionMethod;
        } catch (\ReflectionException $e) {
            throw $e;
        }
    }

    /**
     * @param object $object
     * @param object $argument
     * @throws \InvalidArgumentException
     */
    protected function assertIncorrectDispatchArguments($object, $argument)
    {
        if (!is_object($object) || !is_object($argument)) {
            throw new \InvalidArgumentException('dispatch requires both arguments to be objects');
        }
    }
}
