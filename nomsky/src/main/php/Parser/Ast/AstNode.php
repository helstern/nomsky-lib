<?php namespace Helstern\Nomsky\Parser\Ast;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Parser\TokenPosition;

interface AstNode
{
    /**
     * @param DoubleDispatcherBuilder $dispatcherBuilder
     * @return DoubleDispatcher
     */
    public function buildDoubleDispatcher(DoubleDispatcherBuilder $dispatcherBuilder);

    /**
     * @return TokenPosition
     */
    public function getTextPosition();
}
