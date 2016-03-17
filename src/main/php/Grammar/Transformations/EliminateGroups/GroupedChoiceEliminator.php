<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

use ArrayObject;
use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use SplStack;

class GroupedChoiceEliminator implements GroupedExpressionEliminator
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
     * @return \Helstern\Nomsky\Grammar\Expressions\Choice
     * @throws \Exception
     */
    private function findGroupedChoice(array $items, SplStack $successors)
    {
        $group = $this->findGroup($items, $successors);

        if (is_null($group)) {
            throw new \Exception('no grouped expression was found in the list of expressions');
        }

        $groupedExpression = $group->getExpression();
        if ($groupedExpression instanceof Choice) {
            return $groupedExpression;
        }
        throw new \Exception('first grouped expression is not a choice');
    }

    /**
     * @param array|Expression[] $items
     *
     * @return array| array[]
     * @throws \Exception
     */
    public function removeFromConcatenation(Concatenation $expression, ArrayObject $accumulator)
    {
        $items = $expression->toArray();
        $successors = new SplStack();
        $successors->setIteratorMode(SplStack::IT_MODE_KEEP | SplStack::IT_MODE_LIFO);

        $choice = $this->findGroupedChoice($items, $successors);
        $items = array_slice($items, 0, count($items) - 1 - $successors->count());

        $total = 0;
        foreach ($choice as $expression) {
            $cleaned = [];
            foreach ($items as $item) {
                $cleaned[] = $item;
            }
            $cleaned[] = $expression;
            foreach ($successors as $successor) {
                $cleaned[] = $successor;
            }

            $expression = new Concatenation(array_shift($cleaned), $cleaned);
            $accumulator->append($expression);
            $total++;
        }

        return $total;
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
        $choice = $this->extractChoice($group);
        $total = 0;
        foreach ($choice as $expression) {
            $total++;
            $accumulator->append($expression);
        }
        return $total;
    }

    private function extractChoice(Group $group)
    {
        $groupedExpression = $group->getExpression();
        if ($groupedExpression instanceof Choice) {
            return $groupedExpression;
        }
        throw new \Exception('first grouped expression is not a choice');
    }
}
