<?php namespace Helstern\Nomsky\Parser\Ast;

class StackBasedAstWalker implements AstWalker
{
    /** @var AstNodeVisitorProvider */
    protected $astNodeVisitorProvider;

    /** @var AstNodeWalkStrategy */
    protected $childrenWalkStrategy;

    /**
     * @param AstNodeVisitorProvider $visitActionDispatcher
     * @param AstNodeWalkStrategy $childrenWalkStrategy
     */
    public function __construct(
        AstNodeVisitorProvider $visitActionDispatcher,
        AstNodeWalkStrategy $childrenWalkStrategy
    ) {
        $this->astNodeVisitorProvider = $visitActionDispatcher;
        $this->childrenWalkStrategy = $childrenWalkStrategy;
    }

    /**
     * @param AstNode $astNode
     * @return bool
     * @throws \RuntimeException
     */
    public function walk(AstNode $astNode)
    {
        /** @var AstNode[]|VisitAction[] $stackOfExpressions */
        $initialNode = $astNode;
        $lastAstNode = null;

        $stackOfVisits = array($initialNode);
        $stackHeight = 1;

        do {
            $nodeOrVisitAction = array_pop($stackOfVisits);
            $stackHeight--;

            if ($nodeOrVisitAction instanceof VisitAction) {
                $this->executeVisitAction($nodeOrVisitAction);
                continue;
            }

            $astNode = $nodeOrVisitAction;
            if ($lastAstNode === $astNode) {
                $infiniteLoopException = new \RuntimeException('Infinite loop detected');
                throw $infiniteLoopException;
            } else {
                $lastAstNode = $astNode;
            }

            $visitList = $this->calculateWalkList($astNode);

            //reverse push the children linked list, such that the first child is last in $stackOfExpressions
            foreach ($visitList as $nextVisit) {
                array_push($stackOfVisits, $nextVisit);
                $stackHeight++;
            }

        } while ($stackHeight > 0);

        return true;
    }

    /**
     * @param AstNode $astNode
     * @return boolean false when the walk ended pre-maturely
     */
    public function walkWithDispatch(AstNode $astNode)
    {
        /** @var AstNode[]|VisitAction[] $stackOfExpressions */
        $initialNode = $astNode;
        $stackOfVisits = array($initialNode);
        $stackHeight = 1;

        do {
            $nodeOrVisitAction = array_pop($stackOfVisits);
            $stackHeight--;

            if ($nodeOrVisitAction instanceof VisitAction) {
                $this->executeVisitAction($nodeOrVisitAction);
                continue;
            }

            $astNode = $nodeOrVisitAction;
            $parentNextVisitAction = $this->dispatchVisitAction($astNode);

            /** @var \SplDoublyLinkedList $visitList */
            $visitList = null;
            if (is_null($parentNextVisitAction)) {
                $visitList = $this->determineListOfChildrenVisits($astNode);
            } else {
                $visitList = $this->determineListOfVisits($astNode, $parentNextVisitAction);
            }

            //reverse push the children linked list, such that the first child is last in $stackOfExpressions
            foreach ($visitList as $nextVisit) {
                array_push($stackOfVisits, $nextVisit);
                $stackHeight++;
            }

        } while ($stackHeight > 0);

        return true;
    }

    /**
     * @param VisitAction $visitAction
     */
    protected function executeVisitAction(VisitAction $visitAction)
    {
        $visitAction->execute();
    }

    /**
     * @param AstNode $astNode
     * @return VisitAction|null
     */
    protected function dispatchVisitAction(AstNode $astNode)
    {
        $visitAction = $this->astNodeVisitorProvider->dispatchVisit($astNode);
        return $visitAction;
    }

    /**
     * @param AstNode $parent
     * @return \Traversable
     */
    protected function calculateWalkList(AstNode $parent)
    {
        $list = $this->childrenWalkStrategy->calculateWalkList($parent);
        return $list;
    }
}
