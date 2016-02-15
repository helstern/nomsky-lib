<?php namespace Helstern\Nomsky\Parser\Ast;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Parser\CharPosition;

interface AstNode
{
    /**
     * @param DoubleDispatcherBuilder $dispatcherBuilder
     * @return DoubleDispatcherBuilder
     */
    public function configureDoubleDispatcher(DoubleDispatcherBuilder $dispatcherBuilder);

    /**
     * @param DoubleDispatcher $dispatcher
     * @param object $visitor
     *
     * @return boolean
     */
    public function dispatch(DoubleDispatcher $dispatcher, $visitor);

    /**
     * @return CharPosition
     */
    public function getTextPosition();
}
