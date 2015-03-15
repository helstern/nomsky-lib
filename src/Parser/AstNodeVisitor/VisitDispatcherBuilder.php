<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Dispatcher\ReflectiveDoubleDispatcher;

class VisitDispatcherBuilder implements DoubleDispatcherBuilder
{
    /** @var string */
    protected $argumentType;

    /**
     * @param string $type
     * @return VisitDispatcherBuilder
     */
    public function addDispatchArgumentType($type)
    {
        $this->argumentType = $type;

        return $this;
    }

    /**
     * @return VisitDispatcherBuilder
     */
    public function build()
    {
        $argumentType   = ucfirst($this->argumentType);
        $preDispatcher  = $this->createPreVisitDispatcher($argumentType);
        $dispatcher     = $this->createVisitDispatcher($argumentType);
        $postDispatcher = $this->createPostVisitDispatcher($argumentType);

        $visitDispatcher = new VisitDispatcherBuilder($preDispatcher, $dispatcher, $postDispatcher);
        return $visitDispatcher;
    }

    /**
     * @param string $argumentType
     * @return ReflectiveDoubleDispatcher
     */
    public function createPreVisitDispatcher($argumentType)
    {
        $pattern = 'preVisit%s';
        $methodName = sprintf($pattern, $argumentType);

        $dispatcher = new ReflectiveDoubleDispatcher($methodName);
        return $dispatcher;
    }

    /**
     * @param string $argumentType
     * @return ReflectiveDoubleDispatcher
     */
    public function createVisitDispatcher($argumentType)
    {
        $pattern = 'visit%s';
        $methodName = sprintf($pattern, $argumentType);

        $dispatcher = new ReflectiveDoubleDispatcher($methodName);
        return $dispatcher;
    }

    /**
     * @param string $argumentType
     * @return ReflectiveDoubleDispatcher
     */
    public function createPostVisitDispatcher($argumentType)
    {
        $pattern = 'postVisit%s';
        $methodName = sprintf($pattern, $argumentType);

        $dispatcher = new ReflectiveDoubleDispatcher($methodName);
        return $dispatcher;
    }
}
