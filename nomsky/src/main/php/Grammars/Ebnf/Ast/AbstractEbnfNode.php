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
     * @return DoubleDispatcher
     */
    public function buildDoubleDispatcher(DoubleDispatcherBuilder $dispatcherBuilder)
    {
        $localClassName = $this->getLocalClassName();
        $dispatcherBuilder->addDispatchArgumentType($localClassName);
        $dispatcher = $dispatcherBuilder->build();

        return $dispatcher;
    }

}
