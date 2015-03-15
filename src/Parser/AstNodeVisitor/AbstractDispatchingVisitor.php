<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

abstract class AbstractDispatchingVisitor implements AstNodeVisitor
{
    /**
     * @param AstNode $astNode
     * @return bool false when the dispatching failed because of wrong type of $astNode
     */
    abstract protected function dispatchPreVisit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     * @return bool
     * @throws NonDispatchableVisitException
     */
    public function preVisit(AstNode $astNode)
    {
        if ($this->dispatchPreVisit($astNode)) {
            return true;
        }

        $this->nonDispatchablePreVisit($astNode);

        $exception = $this->createNonDispatchableVisitException($astNode);
        throw $exception;
    }

    /**
     * @param AstNode $astNode
     */
    protected function nonDispatchablePreVisit(AstNode $astNode)
    {}

    /**
     * @param AstNode $astNode
     * @return bool false when the dispatching failed because of wrong type of $astNode
     */
    abstract protected function dispatchVisit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     * @return bool
     * @throws NonDispatchableVisitException
     */
    public function visit(AstNode $astNode)
    {
        if ($this->dispatchVisit($astNode)) {
            return true;
        }

        $this->nonDispatchableVisit($astNode);

        $exception = $this->createNonDispatchableVisitException($astNode);
        throw $exception;
    }

    /**
     * @param AstNode $astNode
     */
    protected function nonDispatchableVisit(AstNode $astNode)
    {}

    /**
     * @param AstNode $astNode
     * @return bool false when the dispatching failed because of wrong type of $astNode
     */
    abstract protected function dispatchPostVisit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     * @return bool
     * @throws NonDispatchableVisitException
     */
    public function postVisit(AstNode $astNode)
    {
        if ($this->dispatchPostVisit($astNode)) {
            return true;
        }

        $this->nonDispatchablePostVisit($astNode);

        $exception = $this->createNonDispatchableVisitException($astNode);
        throw $exception;
    }

    /**
     * @param AstNode $astNode
     */
    protected function nonDispatchablePostVisit(AstNode $astNode)
    {}

    /**
     * @param AstNode $astNode
     * @return NonDispatchableVisitException
     */
    protected function createNonDispatchableVisitException(AstNode $astNode)
    {
        $msg = 'wrong ast node was visited';
        $exception = new NonDispatchableVisitException($astNode, $msg);
        return $exception;
    }
}
