<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Dispatcher\ReflectiveDoubleDispatcher;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

class VisitDispatcher
{
    /** @var ReflectiveDoubleDispatcher */
    protected $preVisitDispatcher;

    /** @var ReflectiveDoubleDispatcher  */
    protected $visitDispatcher;

    /** @var ReflectiveDoubleDispatcher  */
    protected $postVisitDispatcher;

    /**
     * @param ReflectiveDoubleDispatcher $preVisitDispatcher
     * @param ReflectiveDoubleDispatcher $visitDispatcher
     * @param ReflectiveDoubleDispatcher $postVisitDispatcher
     */
    public function __construct(
        ReflectiveDoubleDispatcher $preVisitDispatcher,
        ReflectiveDoubleDispatcher $visitDispatcher,
        ReflectiveDoubleDispatcher $postVisitDispatcher
    ) {
        $this->preVisitDispatcher   = $preVisitDispatcher;
        $this->visitDispatcher      = $visitDispatcher;
        $this->postVisitDispatcher  = $postVisitDispatcher;
    }

    /**
     * @param AstNodeVisitor $visitor
     * @param AstNode $astNode
     * @return bool false when the dispatching failed because of wrong type of $astNode
     */
    public function dispatchPreVisit(AstNodeVisitor $visitor, AstNode $astNode)
    {
        $this->preVisitDispatcher->dispatch($visitor, $astNode);
        return true;
    }

    /**
     * @param AstNodeVisitor $visitor
     * @param AstNode $astNode
     * @return bool false when the dispatching failed because of wrong type of $astNode
     */
    public function dispatchVisit(AstNodeVisitor $visitor, AstNode $astNode)
    {
        $this->visitDispatcher->dispatch($visitor, $astNode);
        return true;
    }

    /**
     * @param AstNodeVisitor $visitor
     * @param AstNode $astNode
     * @return bool false when the dispatching failed because of wrong type of $astNode
     */
    public function dispatchPostVisit(AstNodeVisitor $visitor, AstNode $astNode)
    {
        $this->postVisitDispatcher->dispatch($visitor, $astNode);
        return true;
    }
}
