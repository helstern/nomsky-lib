<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

use ArrayObject;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\Group;
use SplStack;

/**
 * Removes the first grouped concatenation from an expression
 */
class GroupedConcatenationEliminator implements GroupedExpressionEliminator
{
    /**
     * @param array $items
     * @param SplStack $successors
     *
     * @return Group|null
     */
    private function findGroup(array $items, SplStack $successors)
    {
        /** @var Group $group */
        $group = null;
        while (! is_null(key($items)) && is_null($group)) {
            $expression = array_pop($items);
            if ($expression instanceof Group) {
                $group = $expression;
            } else {
                $successors->push($expression);
            }
        }

        return $group;
    }


    /**
     * @param array $items
     * @param \SplStack $successors
     *
     * @return \Helstern\Nomsky\Grammar\Expressions\Concatenation
     * @throws \Exception
     */
    private function findFirstGroupedConcatenation(array $items, SplStack $successors)
    {
        $group = $this->findGroup($items, $successors);

        if (is_null($group)) {
            throw new \Exception('no grouped expression was found in the list of expressions');
        }

        $groupedExpression = $group->getExpression();
        if ($groupedExpression instanceof Concatenation) {
            return $groupedExpression;
        }
        throw new \Exception('first grouped expression is not a choice');
    }

    /**
     * @param array $items
     *
     * @return array
     * @throws \Exception
     */
    private function remove(array $items)
    {
        $successors = new SplStack();
        $successors->setIteratorMode(SplStack::IT_MODE_KEEP | SplStack::IT_MODE_LIFO);

        $concatenation = $this->findFirstGroupedConcatenation($items, $successors);
        $items = array_slice($items, 0, count($items) - 1 - $successors->count());

        $items[] = $concatenation;
        foreach ($successors as $expression) {
            $items[] = $expression;
        }

        return $items;
    }

    public function removeFromConcatenation(Concatenation $expression, ArrayObject $accumulator)
    {
        $items = $expression->toArray();
        $cleaned = $this->remove($items);
        $expression = new Concatenation(array_shift($cleaned), $cleaned);

        $accumulator->append($expression);

        return 1;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Expressions\Group $group
     * @param \ArrayObject $accumulator
     *
     * @return int
     * @throws \Exception
     */
    public function removeGroup(Group $group, ArrayObject $accumulator)
    {
        $concatenation = $this->extractConcatenation($group);
        $accumulator->append($concatenation);
        return 1;
    }

    private function extractConcatenation(Group $group)
    {
        $groupedExpression = $group->getExpression();
        if ($groupedExpression instanceof Concatenation) {
            return $groupedExpression;
        }
        throw new \Exception('grouped expression is not a concatenation');
    }
}
