<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\DoubleDispatcher;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

class DispatchingVisitor implements AstNodeVisitor
{
    /**
     * @var object
     */
    private $visitor;

    /**
     * @var DoubleDispatcher
     */
    private $preVisitDispatcher;

    /**
     * @var DoubleDispatcher
     */
    private $visitDispatcher;

    /**
     * @var DoubleDispatcher
     */
    private $postVisitDispatcher;

    /**
     * @param $visitor
     * @param DoubleDispatcher $preVisitDispatcher
     * @param DoubleDispatcher $visitDispatcher
     * @param DoubleDispatcher $postVisitDispatcher
     */
    public function __construct(
        $visitor,
        DoubleDispatcher $preVisitDispatcher,
        DoubleDispatcher $visitDispatcher,
        DoubleDispatcher $postVisitDispatcher
    ) {

        $this->visitor = $visitor;
        $this->preVisitDispatcher = $preVisitDispatcher;
        $this->visitDispatcher = $visitDispatcher;
        $this->postVisitDispatcher = $postVisitDispatcher;
    }

    /**
     * @param AstNode $astNode
     *
     * @return boolean
     */
    public function preVisit(AstNode $astNode)
    {
        return $astNode->dispatch($this->preVisitDispatcher, $this->visitor);
    }

    /**
     * @param AstNode $astNode
     *
     * @return boolean
     */
    public function visit(AstNode $astNode)
    {
        return $astNode->dispatch($this->visitDispatcher, $this->visitor);
    }

    /**
     * @param AstNode $astNode
     *
     * @return boolean
     */
    public function postVisit(AstNode $astNode)
    {
        return $astNode->dispatch($this->postVisitDispatcher, $this->visitor);
    }
}
