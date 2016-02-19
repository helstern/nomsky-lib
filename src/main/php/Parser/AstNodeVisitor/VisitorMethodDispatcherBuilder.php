<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Dispatcher\ReflectionMethodDispatcher;

class VisitorMethodDispatcherBuilder implements DoubleDispatcherBuilder
{
    /** @var \ReflectionClass */
    private $argumentType;

    /**
     * @var \ReflectionClass
     */
    private $dispatcherType;

    /**
     * @param object|string $type
     *
     * @return VisitorMethodDispatcherBuilder
     */
    public function setDispatcherType($type)
    {
        $this->dispatcherType = new \ReflectionClass($type);
        return $this;
    }

    /**
     * @param string $type
     *
     * @return VisitorMethodDispatcherBuilder
     */
    public function addDispatchArgumentType($type)
    {
        $this->argumentType = new \ReflectionClass($type);
        return $this;
    }

    /**
     * @return ReflectionMethodDispatcher
     */
    public function build()
    {
        $methodName = $this->getMethodName();
        if ($this->verifyTypes($this->dispatcherType, $methodName, $this->argumentType)) {
            $reflection = $this->dispatcherType->getMethod($methodName);
            $dispatcher =  new ReflectionMethodDispatcher($reflection, $this->argumentType);
            return $dispatcher;
        }
        return null;
    }

    /**
     * @param \ReflectionClass $dispatchType
     * @param string $methodName
     * @param \ReflectionClass $argumentType
     *
     * @return bool
     */
    private function verifyTypes(\ReflectionClass $dispatchType, $methodName, \ReflectionClass $argumentType)
    {
        $method = null;
        if ($dispatchType->hasMethod($methodName)) {
            $method = $dispatchType->getMethod($methodName);
        }

        if (is_null($method) || !$method->isPublic() || 0 == $method->getNumberOfParameters()) {
            return false;
        }

        $parameters = $method->getParameters();
        $parameter = $parameters[0];

        if ($parameter->getClass()->getName() != $argumentType->getName()) {
            return false;
        }

        for ($i = 1; $i < count($parameters); $i++ ) {
            $parameter = $parameters[$i];
            if (! $parameter->isOptional()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    private function getMethodName()
    {
        $methodSuffix = $this->argumentType->getShortName();
        $methodSuffix = ucfirst($methodSuffix);

        $pattern = 'get%sVisitor';
        $methodName = sprintf($pattern, $methodSuffix);

        return $methodName;
    }
}
