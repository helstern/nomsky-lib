<?php namespace Helstern\Nomsky\Parser\Ast;

class WalkActionStack implements WalkListItemCollector
{
    /**
     * @var array|WalkAction
     */
    private $stack = [];

    /**
     * @var \SplDoublyLinkedList
     */
    private $collected;

    public function __construct()
    {
        $list = new \SplDoublyLinkedList();
        $list->setIteratorMode(\SplDoublyLinkedList::IT_MODE_LIFO | \SplDoublyLinkedList::IT_MODE_DELETE);

        $this->collected = $list;
    }

    /**
     * @return WalkAction
     */
    public function pop()
    {
        $walkAction = array_pop($this->stack);
        return $walkAction;
    }

    /**
     * @param array|WalkAction[] $items
     *
     * @return int
     */
    public function collectList(array $items)
    {
        foreach ($items as $item) {
            $this->collect($item);
        }

        return count($items);
    }

    /**
     * Adds $item to the inner collection of collected items
     *
     * @param WalkAction $item
     *
     * @return bool
     */
    public function collect(WalkAction $item)
    {
        $this->collected->push($item);
        return true;
    }

    /**
     * Stacks the collected items and returns the number of new items
     *
     * @return int
     */
    public function stack()
    {
        $stackedCount = 0;

        //reverse push the children linked list, such that the first child is last in $stackOfActions
        foreach ($this->collected as $nextAction) {
            array_push($this->stack, $nextAction);
            $stackedCount++;
        }
        return $stackedCount;
    }
}
