<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitorProvider;

abstract class AbstractDispatchingProvider implements AstNodeVisitorProvider
{
    /**
     * @param AstNode $node
     * @return VisitDispatcher
     */
    public function createVisitDispatcher(AstNode $node)
    {
        $visitDispatcherBuilder = new VisitDispatcherBuilder();
        /** @var VisitDispatcher $visitDispatcher */
        $visitDispatcher = $node->buildDoubleDispatcher($visitDispatcherBuilder);

        return $visitDispatcher;
    }

    /**
     * @param AstNode $node
     * @return AstNodeVisitor
     */
    public function getVisitor(AstNode $node)
    {
        $dispatcherBuilder = new VisitorProviderDispatcherBuilder();
        $dispatcher = $node->buildDoubleDispatcher($dispatcherBuilder);

        $visitor = $this->dispatch($dispatcher, $node);
        return $visitor;
    }

    /**
     * @param DoubleDispatcher $dispatcher
     * @param object $argument
     * @return AstNodeVisitor
     */
    protected function dispatch(DoubleDispatcher $dispatcher, $argument)
    {
        /** @var AstNodeVisitor $visitor */
        $visitor = $dispatcher->dispatch($this, $argument);
        return $visitor;
    }

}
