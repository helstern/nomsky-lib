<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeWalkStrategy;
use Helstern\Nomsky\Parser\Ast\VisitActionFactory;

class PreOrderVisitStrategy implements AstNodeWalkStrategy
{
    /** @var AstNodeVisitorProvider */
    private $visitorProvider;

    /**
     * @var AstNodeVisitorProvider
     */
    private $visits;

    /**
     * @param AstNodeVisitorProvider $astNodeVisitorProvider
     *
     * @return PreOrderVisitStrategy
     */
    public static function newDefaultInstance(AstNodeVisitorProvider $astNodeVisitorProvider)
    {
        return new self($astNodeVisitorProvider, new VisitActions());
    }

    /**
     * @param AstNodeVisitorProvider $astNodeVisitorProvider
     * @param VisitActionFactory $visits
     */
    public function __construct(
        AstNodeVisitorProvider $astNodeVisitorProvider,
        VisitActionFactory $visits
    ) {
        $this->visitorProvider = $astNodeVisitorProvider;
        $this->visits = $visits;
    }

    /**
     * @param AstNode $parent
     * @return \SplDoublyLinkedList|\Traversable
     */
    public function calculateWalkList(AstNode $parent)
    {
        $visitor = $this->visitorProvider->getVisitor($parent);
        if (is_null($visitor)) {
            return [];
        }

        $list = null;
        if ($parent instanceof CompositeAstNode) {
            $list = $this->buildParentNodeList($parent, $visitor);
        } else {
            $list = $this->buildChildlessNodeList($parent, $visitor);
        }

        return $list;
    }

    /**
     * @param CompositeAstNode $parent
     * @param AstNodeVisitor $visitor
     *
     * @return \SplDoublyLinkedList
     */
    private function buildParentNodeList(CompositeAstNode $parent, AstNodeVisitor $visitor)
    {
        $list = new \SplDoublyLinkedList();
        $list->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        $actionsHead = [
            $this->visits->createPreVisit($parent, $visitor),
            $this->visits->createActualVisit($parent, $visitor)
        ];
        foreach ($actionsHead as $action) {
            $list->push($action);
        }

        $this->addChildrenActionsToList($parent, $list);

        $tailAction = $this->visits->createPostVisit($parent, $visitor);
        $list->push($tailAction);

        return $list;
    }

    /**
     * @param CompositeAstNode $parent
     * @param \SplDoublyLinkedList $list
     * @return bool
     */
    private function addChildrenActionsToList(CompositeAstNode $parent, \SplDoublyLinkedList $list)
    {
        $addToListResult = false;

        $children = $parent->getChildren();
        foreach($children as $child) {
            $list->push($child);
            $addToListResult = true;
        }

        return $addToListResult;
    }

    /**
     * @param AstNode $node
     * @param AstNodeVisitor $visitor
     *
     * @return \SplDoublyLinkedList
     */
    private function buildChildlessNodeList(AstNode $node, AstNodeVisitor $visitor)
    {
        $list = new \SplDoublyLinkedList();
        $list->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        $visit = $this->visits->createPreVisit($node, $visitor);
        $list->push($visit);

        $visit = $this->visits->createActualVisit($node, $visitor);
        $list->push($visit);

        $visit = $this->visits->createPostVisit($node, $visitor);
        $list->push($visit);

        return $list;
    }
}
