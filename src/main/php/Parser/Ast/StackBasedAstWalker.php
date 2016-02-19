<?php namespace Helstern\Nomsky\Parser\Ast;

class StackBasedAstWalker implements AstWalker
{
    /** @var AstNodeWalkStrategy */
    private $walkStrategy;

    /**
     * @var WalkerStateMachine
     */
    private $walkStateMachine;

    /**
     * @param AstNodeWalkStrategy $childrenWalkStrategy
     * @param WalkerStateMachine $walkerStateMachine
     */
    public function __construct(AstNodeWalkStrategy $childrenWalkStrategy, WalkerStateMachine $walkerStateMachine = null)
    {
        $this->walkStrategy = $childrenWalkStrategy;
        if (is_null($walkerStateMachine)) {
            $this->walkStateMachine = new WalkerStateMachine();
        }
    }

    /**
     * @param AstNode $astNode
     * @return bool
     * @throws \RuntimeException
         */
    public function walk(AstNode $astNode)
    {
        /** @var WalkAction[] $stackOfExpressions */
        $initialNode = $astNode;
        $lastAstNode = null;

        $stack = new WalkActionStack();
        $stack->collect(new CalculateWalkListAction($initialNode));
        $stackHeight = $stack->stack();

        $this->walkStateMachine->walk($initialNode);

        /** @var WalkAction $walkAction */
        $walkAction = null;
        do {
            $walkAction = $stack->pop();
            $stackHeight--;

            if ($walkAction->execute()) {
                continue;
            }

            $astNode = $walkAction->getSubject();
            if ($lastAstNode === $astNode) {
                $infiniteLoopException = new \RuntimeException('Infinite loop detected');
                throw $infiniteLoopException;
            } else {
                $lastAstNode = $astNode;
            }

            $state = $this->walkStrategy->calculateWalkList($astNode, $stack, $this->walkStateMachine);
            if ($state == WalkerStateMachine::STATE_IGNORE) {
                continue;
            }

            $stackedCount = $stack->stack();
            $stackHeight = $stackHeight + $stackedCount;

        } while ($stackHeight > 0);

        return true;
    }
}
