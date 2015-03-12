<?php namespace Helstern\Nomsky\Parser\AstNodeWalkStrategy;

use Helstern\Nomsky\Parser\Ast\AbstractCompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitorProvider;
use Helstern\Nomsky\Parser\Ast\AstNodeWalkStrategy;

class PreOrderWalkStrategy implements AstNodeWalkStrategy
{
    /** @var AstNodeVisitorProvider */
    protected $astNodeVisitorProvider;

    /**
     * @param AstNodeVisitorProvider $astNodeVisitorProvider
     */
    public function __construct(AstNodeVisitorProvider $astNodeVisitorProvider)
    {
        $this->astNodeVisitorProvider = $astNodeVisitorProvider;
    }

    /**
     * @return VisitActions
     */
    public function visits()
    {
        $visitsFactory = new VisitActions($this->astNodeVisitorProvider);
        return $visitsFactory;
    }

    /**
     * @param AstNode $parent
     * @return \SplDoublyLinkedList|\Traversable
     */
    public function calculateWalkList(AstNode $parent)
    {
        $list = null;
        if ($parent instanceof AbstractCompositeAstNode) {
            $list = $this->buildParentNodeList($parent);
        } else {
            $list = $this->buildChildlessNodeList($parent);
        }

        return $list;
    }

    /**
     * @param AbstractCompositeAstNode $parent
     * @param \SplDoublyLinkedList $list
     * @return bool
     */
    protected function addChildrenActionsToList(AbstractCompositeAstNode $parent, \SplDoublyLinkedList $list)
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
     * @param AbstractCompositeAstNode $parent
     * @return \SplDoublyLinkedList
     */
    protected function buildParentNodeList(AbstractCompositeAstNode $parent)
    {
        $visits = $this->visits();

        $list = new \SplDoublyLinkedList();
        $list->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        $headActions = [$visits->createPreVisit($parent), $visits->createActualVisit($parent)];
        foreach ($headActions as $action) {
            $list->push($action);
        }

        $this->addChildrenActionsToList($parent, $list);

        $tailAction = $visits->createPostVisit($parent);
        $list->push($tailAction);

        return $list;
    }

    /**
     * @param AstNode $node
     * @return \SplDoublyLinkedList
     */
    protected function buildChildlessNodeList(AstNode $node)
    {
        $list = new \SplDoublyLinkedList();
        $list->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_KEEP);

        $visits = $this->visits();

        $visit = $visits->createPreVisit($node);
        $list->push($visit);

        $visit = $visits->createActualVisit($node);
        $list->push($visit);

        $visit = $visits->createPostVisit($node);
        $list->push($visit);

        return $list;
    }
}
