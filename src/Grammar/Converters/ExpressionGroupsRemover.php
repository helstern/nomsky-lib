<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Group;

class ExpressionGroupsRemover {

    /** @var Combinator */
    protected $childrenCombinator;

    /**
     * @param Combinator $childrenCombinator
     */
    public function __construct(Combinator $childrenCombinator)
    {
        $this->childrenCombinator = $childrenCombinator;
    }

    /**
     * @param array $children
     * @param array $combinators
     * @return bool
     */
    public function shouldGroupsBeRemoved(array $children, array $combinators)
    {
        return !empty($combinators);
    }

    /**
     * @param array $children
     * @param Combinator[] $combinators
     * @param ExpressionCombinatorBuilder $builder
     * @return ExpressionCombinatorBuilder
     * @throws \Exception
     */
    public function removeGroups(array $children, array $combinators, ExpressionCombinatorBuilder $builder)
    {
        if (!$this->shouldGroupsBeRemoved($children, $combinators)) {
            throw new \Exception('no reason to call this method');
        }

        /** @var array $head */
        $head = null;
        $queue = $children;
        do {
            $remaining = count($queue);
            $head      = array();

            //non group children
            $child = array_shift($queue);
            $remaining--;
            while (false == $child instanceof Group) {
                $head[] = $child;

                $child = array_shift($queue);
                $remaining--;
            }

            //no group found, last one was removed in previous iteration
            if (false == $child instanceof Group) {
                continue;
            }

            /** @var Combinator $childCombinator */
            $childCombinator    = array_shift($combinators);
            /** @var Group $group */
            $group              = $child;

            //group is preceded by non-group siblings
            if (! empty($head)) {
                $combination    = $this->childrenCombinator->createCombinationWith($childCombinator);
                $newCombinator  = $combination->getCombinator();
                array_unshift($combinators, $newCombinator);

                $groupItems     = $this->extractListOfChildren($group);
                $newGroupItems  = $combination->combine($head, $groupItems);

                $builder->setChildren($newGroupItems);
                $builder->setCombinator($newCombinator);
                $group = $builder->buildGroup();

                array_unshift($queue, $group);

                continue;
            }

            //group is last
            if ($remaining === 0) {
                continue;
            }

            //group is preceded by another group
            $nextSibling = reset($queue);
            if ($nextSibling instanceof Group) {

                $nextSibling = array_shift($queue);
                $remaining--;

                $siblingGroupCombinator = array_shift($combinators);
                $combination = $childCombinator->createCombinationWith($siblingGroupCombinator);
                $newCombinator = $combination->getCombinator();
                array_unshift($combinators, $newCombinator);

                $groupItems     = $this->extractListOfChildren($group);
                $siblingItems   = $this->extractListOfChildren($nextSibling);
                $newGroupItems  = $combination->combine($groupItems, $siblingItems);

                $builder->setChildren($newGroupItems);
                $builder->setCombinator($newCombinator);
                $group = $builder->buildGroup();

                array_unshift($queue, $group);

                continue;
            }

            //group followed by non-group siblings
            $nextSiblings = array();
            do {
                $nextSiblings[] = array_shift($queue);
                $remaining--;

                $nextSibling = reset($queue);
            } while ($remaining > 0 && false == $nextSibling instanceof Group);

            $combination    = $childCombinator->createCombinationWith($this->childrenCombinator);
            $groupItems = $this->extractListOfChildren($group);

            $newCombinator = $combination->getCombinator();
            array_unshift($combinators, $newCombinator);

            $newGroupItems = $combination->combine($groupItems, $nextSiblings);

            $builder->setChildren($newGroupItems);
            $builder->setCombinator($newCombinator);
            $group = $builder->buildGroup();

            array_unshift($queue, $group);

        } while ($remaining > 0);
    }

    /**
     * @param Group $group
     * @return array|Expression[]
     */
    protected function extractListOfChildren(Group $group)
    {
        $groupExpression = $group->getExpression();
        if ($groupExpression instanceof ExpressionIterable) {
            $listOfItems  = iterator_to_array($groupExpression->getIterator());
        } else {
            $listOfItems  = array($groupExpression);
        }

        return $listOfItems;
    }

}
