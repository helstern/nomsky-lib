<?php namespace Helstern\Nomsky\Parser\Ast;

class StackBasedAstWalker implements AstWalker
{
    /** @var AstNodeWalkStrategy */
    protected $childrenWalkStrategy;

    /**
     * @param AstNodeWalkStrategy $childrenWalkStrategy
     */
    public function __construct(AstNodeWalkStrategy $childrenWalkStrategy)
    {
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
     * @param VisitAction $visitAction
     */
    protected function executeVisitAction(VisitAction $visitAction)
    {
        $visitAction->execute();
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
