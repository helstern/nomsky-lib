<?php namespace Helstern\Nomsky\Parser\AstNodeVisitor;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;

abstract class AbstractDispatchingVisitor implements AstNodeVisitor
{
    /**
     * @return VisitDispatcher
     */
    abstract protected function getVisitDispatcher();

    /**
     * @param AstNode $astNode
     * @return bool
     * @throws NonDispatchableVisitException
     */
    public function preVisit(AstNode $astNode)
    {
        $visitDispatcher = $this->getVisitDispatcher();
        if ($visitDispatcher->dispatchPreVisit($this, $astNode)) {
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
     * @return bool
     * @throws NonDispatchableVisitException
     */
    public function visit(AstNode $astNode)
    {
        $visitDispatcher = $this->getVisitDispatcher();
        if ($visitDispatcher->dispatchVisit($this, $astNode)) {
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
     * @return bool
     * @throws NonDispatchableVisitException
     */
    public function postVisit(AstNode $astNode)
    {
        $visitDispatcher = $this->getVisitDispatcher();
        if ($visitDispatcher->dispatchPostVisit($this, $astNode)) {
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
