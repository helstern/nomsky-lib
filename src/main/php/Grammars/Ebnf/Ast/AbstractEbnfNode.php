<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Dispatcher\DoubleDispatcherBuilder;
use Helstern\Nomsky\Parser\Ast\AstNode;

abstract class AbstractEbnfNode implements AstNode
{
    private function getLocalClassName()
    {
        $className = get_class($this);
        $localClassNameStart = strrpos($className, '\\');
        if (false === $localClassNameStart) {
            return $className;
        }

        $localClassName = substr($className, $localClassNameStart + strlen('\\'));
        return $localClassName;
    }

    /**
     * @param DoubleDispatcherBuilder $dispatcherBuilder
     * @return DoubleDispatcherBuilder
     */
    public function configureDoubleDispatcher(DoubleDispatcherBuilder $dispatcherBuilder)
    {
        $dispatcherBuilder->addDispatchArgumentType($this);
        return $dispatcherBuilder;
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
