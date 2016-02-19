<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\CharPosition;

class FakeAstNode implements AstNode
{
    /**
     * @param DoubleDispatcherBuilder $dispatcherBuilder
     * @return DoubleDispatcher
     */
    public function configureDoubleDispatcher(DoubleDispatcherBuilder $dispatcherBuilder)
    {
        $dispatcherBuilder->addDispatchArgumentType($this);
        return $dispatcherBuilder;
    }

    /**
     * @return CharPosition
     */
    public function getTextPosition()
    {
        return new CharPosition(0, 0, 0);
    }

    /**
     * @param DoubleDispatcher $dispatcher
     * @param object $visitor
     *
     * @return boolean
     */
    public function dispatch(DoubleDispatcher $dispatcher, $visitor)
    {
        return $dispatcher->dispatch($visitor, $this);
    }
}
