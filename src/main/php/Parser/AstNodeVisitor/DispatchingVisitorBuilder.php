<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Dispatcher\ReflectionMethodDispatcher;

class DispatchingVisitorBuilder implements DoubleDispatcherBuilder
{
    /** @var \ReflectionClass */
    private $argumentType;

    /**
     * @var object
     */
    private $visitor;

    /**
     * @var \ReflectionClass
     */
    private $dispatcherType;

    /**
     * @param object|string $instance
     *
     * @return DispatchingVisitorBuilder
     */
    public function setVisitor($instance)
    {
        $this->visitor = $instance;
        $this->dispatcherType = new \ReflectionClass($instance);
        return $this;
    }

    /**
     * @param string $type
     *
     * @return DispatchingVisitorBuilder
     */
    public function addDispatchArgumentType($type)
    {
        $this->argumentType = new \ReflectionClass($type);
        return $this;
    }

    /**
     * @return DispatchingVisitor
     */
    public function build()
    {
        $preDispatcher  = $this->createDispatcher($this->dispatcherType, 'preVisit%s', $this->argumentType);
        $dispatcher     = $this->createDispatcher($this->dispatcherType, 'visit%s', $this->argumentType);
        $postDispatcher = $this->createDispatcher($this->dispatcherType, 'postVisit%s', $this->argumentType);

        $visitor = new DispatchingVisitor($this->visitor, $preDispatcher, $dispatcher, $postDispatcher);
        return $visitor;
    }

    /**
     * @param \ReflectionClass $dispatcherType
     * @param string $sprintfPattern
     * @param \ReflectionClass $argumentType
     *
     * @return ReflectionMethodDispatcher
     */
    private function createDispatcher(\ReflectionClass $dispatcherType, $sprintfPattern, \ReflectionClass $argumentType)
    {
        $methodName = $this->buildMethodName($sprintfPattern, $argumentType);
        $reflection = $dispatcherType->getMethod($methodName);

        $dispatcher = new ReflectionMethodDispatcher($reflection);
        return $dispatcher;
    }

    /**
     * @param string $sprintfPattern
     * @param \ReflectionClass $argumentType
     *
     * @return string
     */
    private function buildMethodName($sprintfPattern, \ReflectionClass $argumentType)
    {
        $methodSuffix = $argumentType->getShortName();
        $methodSuffix   = ucfirst($methodSuffix);

        $methodName = sprintf($sprintfPattern, $methodSuffix);
        return $methodName;
    }
}

