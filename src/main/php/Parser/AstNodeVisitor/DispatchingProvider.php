<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitStrategy\AstNodeVisitorProvider;

class DispatchingProvider implements AstNodeVisitorProvider
{
    /**
     * @var object
     */
    private $visitors;

    public function __construct($visitors)
    {
        $this->visitors = $visitors;
    }

    /**
     * @param AstNode $node
     * @return DoubleDispatcher|null
     */
    private function getDispatcher(AstNode $node)
    {
        $builder = new VisitorMethodDispatcherBuilder();
        $builder->setDispatcherType($this->visitors);
        $node->configureDoubleDispatcher($builder);

        $dispatcher = $builder->build();
        return $dispatcher;
    }

    /**
     * @param AstNode $node
     * @return AstNodeVisitor
     */
    public function getVisitor(AstNode $node)
    {
        $dispatcher = $this->getDispatcher($node);
        if (is_null($dispatcher)) {
            return null;
        }

        $visitor = $dispatcher->dispatch($this->visitors, $node);
        if ($visitor instanceof AstNodeVisitor) {
            return $visitor;
        }

        return null;
    }
}
