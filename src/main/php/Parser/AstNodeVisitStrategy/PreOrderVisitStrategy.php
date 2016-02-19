<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\Ast\CalculateWalkListAction;
use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeWalkStrategy;
use Helstern\Nomsky\Parser\Ast\WalkerStateMachine;
use Helstern\Nomsky\Parser\Ast\WalkListItemCollector;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitActions;

class PreOrderVisitStrategy implements AstNodeWalkStrategy
{
    /** @var AstNodeVisitorProvider */
    private $visitorProvider;

    /**
     * @var bool
     */
    private $skipMissing;

    /**
     * @var AstNodeVisitorProvider
     */
    private $visits;

    /**
     * @param AstNodeVisitorProvider $astNodeVisitorProvider
     *
     * @param bool $skipMissing
     *
     * @return PreOrderVisitStrategy
     */
    public static function newDefaultInstance(AstNodeVisitorProvider $astNodeVisitorProvider, $skipMissing = true)
    {
        return new self($astNodeVisitorProvider, $skipMissing, new VisitActions());
    }

    /**
     * @param AstNodeVisitorProvider $visitorProvider
     * @param boolean $skipMissing
     * @param VisitActionFactory $visits
     */
    public function __construct(AstNodeVisitorProvider $visitorProvider, $skipMissing, VisitActionFactory $visits)
    {
        $this->visitorProvider = $visitorProvider;
        $this->skipMissing = (bool) $skipMissing;
        $this->visits = $visits;
    }

    public function calculateWalkList(AstNode $parent, WalkListItemCollector $walkListCollector, WalkerStateMachine $walkerStates)
    {
        $visitor = $this->visitorProvider->getVisitor($parent);
        if (is_null($visitor) && $this->skipMissing) {
            $walkerStates->ignore($parent);
            $walkerState = $walkerStates->getState();
            return $walkerState;
        }

        if (is_null($visitor)) {
            throw new \InvalidArgumentException('can not find a proper visitor');
        }

        if ($parent instanceof CompositeAstNode && 0 < $parent->countChildren()) {
            $this->collectParentNodeActions($parent, $visitor, $walkListCollector);

            $walkerStates->walk($parent);
            $walkerState = $walkerStates->getState();
            return $walkerState;
        }

        $this->collectChildlessNodeActions($parent, $visitor, $walkListCollector);
        $walkerStates->walk($parent);
        $walkerState = $walkerStates->getState();
        return $walkerState;
    }

    /**
     * @param CompositeAstNode $parent
     * @param AstNodeVisitor $visitor
     * @param WalkListItemCollector $walkListCollector
     *
     * @return int
     */
    private function collectParentNodeActions(
        CompositeAstNode $parent,
        AstNodeVisitor $visitor,
        WalkListItemCollector $walkListCollector
    ) {
        $actionsHead = [
            $this->visits->createPreVisit($parent, $visitor),
            $this->visits->createActualVisit($parent, $visitor)
        ];
        $walkListCollector->collectList($actionsHead);

        $nrActions = $this->collectChildrenActions($parent, $walkListCollector);

        $tailAction = $this->visits->createPostVisit($parent, $visitor);
        $walkListCollector->collect($tailAction);

        return 3 + $nrActions;
    }

    /**
     * @param CompositeAstNode $parent
     * @param WalkListItemCollector $walkListCollector
     *
     * @return int
     */
    private function collectChildrenActions(CompositeAstNode $parent, WalkListItemCollector $walkListCollector)
    {
        $children = $parent->getChildren();
        foreach($children as $child) {
            $action = new CalculateWalkListAction($child);
            $walkListCollector->collect($action);
        }

        return count($children);
    }

    /**
     * @param AstNode $node
     * @param AstNodeVisitor $visitor
     * @param WalkListItemCollector $walkListCollector
     *
     * @return int
     */
    private function collectChildlessNodeActions(
        AstNode $node,
        AstNodeVisitor $visitor,
        WalkListItemCollector $walkListCollector
    ) {
        $visit = $this->visits->createPreVisit($node, $visitor);
        $walkListCollector->collect($visit);

        $visit = $this->visits->createActualVisit($node, $visitor);
        $walkListCollector->collect($visit);

        $visit = $this->visits->createPostVisit($node, $visitor);
        $walkListCollector->collect($visit);

        return 3;
    }
}
