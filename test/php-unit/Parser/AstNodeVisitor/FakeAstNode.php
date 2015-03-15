<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class FakeAstNode implements AstNode
{
    /**
     * @param DoubleDispatcherBuilder $dispatcherBuilder
     * @return DoubleDispatcher
     */
    public function buildDoubleDispatcher(DoubleDispatcherBuilder $dispatcherBuilder)
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $dispatcherBuilder->addDispatchArgumentType($className);
        $dispatcher = $dispatcherBuilder->build();

        return $dispatcher;
    }

    /**
     * @return TextPosition
     */
    public function getTextPosition()
    {
        return new TextPosition(0, 0, 0);
    }
}
