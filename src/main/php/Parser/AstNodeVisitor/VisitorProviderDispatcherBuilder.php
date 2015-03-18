<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Dispatcher\ReflectiveDoubleDispatcher;

class VisitorProviderDispatcherBuilder implements  DoubleDispatcherBuilder
{
    /** @var string */
    protected $dispatchArgumentType;

    /**
     * @param string $type
     * @return VisitorProviderDispatcherBuilder
     */
    public function addDispatchArgumentType($type)
    {
        $this->dispatchArgumentType = $type;

        return $this;
    }

    /**
     * @return ReflectiveDoubleDispatcher
     */
    public function build()
    {
        $visitorProviderMethod = $this->getMethodName();
        $dispatcher =  new ReflectiveDoubleDispatcher($visitorProviderMethod);

        return $dispatcher;
    }

    /**
     * @return string
     */
    protected function getMethodName()
    {
        $methodPattern = 'get%sVisitor';
        $methodName = sprintf($methodPattern, ucfirst($this->dispatchArgumentType));

        return $methodName;
    }
}
