<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionAggregate;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

class EliminateGroupsVisitor extends AbstractErrorTriggeringVisitor implements HierarchyVisitor
{
    /** @var Expression */
    protected $root;

    /** @var Expression[] */
    protected $expressions;

    /** @var array[] */
    protected $stackOfChildren;

    /** @var CombinatorAdapterForExpressionGroup[] */
    protected $stackOfCombinators;

    protected $parentIsGroup = false;

    /**
     * @return Expression|null
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param Expression $e
     */
    protected function setAsRootOrAddToStackOfChildren(Expression $e)
    {
        if (empty($this->stackOfChildren)) {
            $this->root = $e;
        } else {
            $this->addToStackOfChildren($e);
        }
    }

    /**
     * @param Expression $e
     */
    protected function addToStackOfChildren(Expression $e)
    {
        /** @var Expression[]|array $parentChildren */
        $parentChildren = array_pop($this->stackOfChildren);
        array_push($parentChildren, $e);
        array_push($this->stackOfChildren, $parentChildren);
    }

    /**
     * @param Expression $e
     * @param array $items
     * @throws \Exception
     */
    protected function onAfterEndVisitExpressionIterable(Expression $e, array $items)
    {
        /** @var array $head */
        $head = null;
        $tail = $items;
        do {
            $processed  = 0;
            $remaining  = count($tail);
            $groupFound = false;
            $head       = array();

            do {
                $item = array_shift($tail);
                $remaining--;

                if (is_array($item)) {
                    $groupFound = true;
                    if ($processed === 0) {
                        $tail = $this->eliminateGroup($item, $tail);
                   } elseif ($remaining === 0) {
                        $tail = $this->eliminateGroup($head, $item);
                    } else {
                        $head = $this->eliminateGroup($head, $item);
                        $tail = array_merge($head, $tail);
                    }
                } else {
                    $head[] = $item;
                }

                $processed++;
            } while ($remaining > 0 && $groupFound == false );
        } while($groupFound == true);

        /** @var CombinatorAdapterForExpressionGroup $combinatorAdapter */
        $combinatorAdapter = array_pop($this->stackOfCombinators);
        if ($combinatorAdapter->expressionIsChildOfGroup()) {
            array_push($this->stackOfCombinators, $combinatorAdapter);
            array_push($this->stackOfChildren, $head);
            return;
        }

        $combinator = $combinatorAdapter->getCombinator();
        $expression = $combinator->createExpression($head);
        $this->setAsRootOrAddToStackOfChildren($expression);
    }

    /**
     * @param array $first
     * @param array $second
     * @return array|null whether the operation eliminated a group or nit
     */
    protected function eliminateGroup(array $first, array $second)
    {
        if (empty($first)) {
            return null;
        }

        /** @var CombinatorAdapterForExpressionGroup $secondCombinatorAdapter */
        $secondCombinatorAdapter   = array_pop($this->stackOfCombinators);
        /** @var CombinatorAdapterForExpressionGroup $firstCombinatorAdapter */
        $firstCombinatorAdapter    = array_pop($this->stackOfCombinators);
        $combination = $firstCombinatorAdapter->createCombination($secondCombinatorAdapter);

        $combinator         = $combination->getCombinator();
        $combinatorAdapter  = $this->adaptCombinator($combinator, $firstCombinatorAdapter->expressionIsChildOfGroup());
        array_push($this->stackOfCombinators, $combinatorAdapter);

        $children           = $combination->combine($first, $second);
        return $children;
    }

    /**
     * @param Combinator $combinator
     * @param bool $groupVisitedLast
     * @return CombinatorAdapterForExpressionGroup
     */
    protected function adaptCombinator(Combinator $combinator, $groupVisitedLast)
    {
        $adapter = new CombinatorAdapterForExpressionGroup($combinator, $groupVisitedLast);
        return $adapter;
    }

    public function startVisitAlternation(Alternation $expression)
    {
        if (!$this->parentIsGroup) {
            $this->stackOfChildren[]    = array();
        }

        $combinator                 = new CombinatorForAlternation();
        $adapter                    = $this->adaptCombinator($combinator, $this->parentIsGroup);
        $this->stackOfCombinators[] = $adapter;

        $this->parentIsGroup = false;

        return true;
    }

    public function endVisitAlternation(Alternation $expression)
    {
        $items          = array_pop($this->stackOfChildren);
        $this->onAfterEndVisitExpressionIterable($expression, $items);

        return true;
    }

    public function startVisitSequence(Sequence $expression)
    {
        if (!$this->parentIsGroup) {
            $this->stackOfChildren[] = array();
        }

        $combinator                 = new CombinatorForSequence();
        $adapter                    = $this->adaptCombinator($combinator, $this->parentIsGroup);
        $this->stackOfCombinators[] = $adapter;

        $this->parentIsGroup = false;

        return true;
    }

    public function endVisitSequence(Sequence $expression)
    {
        $items      = array_pop($this->stackOfChildren);
        $this->onAfterEndVisitExpressionIterable($expression, $items);

        return true;
    }

    public function startVisitGroup(Group $expression)
    {
        $this->stackOfChildren[] = array();
        $this->parentIsGroup = true;

        return true;
    }

    public function endVisitGroup(Group $expression)
    {
        $groupItems = array_pop($this->stackOfChildren);
        $parentChildren = array_pop($this->stackOfChildren);

        array_push($parentChildren, $groupItems);
        array_push($this->stackOfChildren, $parentChildren);

        return true;
    }

    public function visitExpression(Expression $expression)
    {
        /** @var array $lastListOfSymbols */
        $lastListOfSymbols = array_pop($this->stackOfChildren);
        $lastListOfSymbols[] = $expression;
        array_push($this->stackOfChildren, $lastListOfSymbols);

        return true;
    }

    /**
     * @param string $method
     * @return string
     */
    protected function getMethodNotCalledWarningMessage($method)
    {
        $warningMessageTemplate = 'method %s should not be called';
        $warningMessage = sprintf($warningMessageTemplate, $method);

        return $warningMessage;
    }
}


/**

(a | b | c | (1 | 2 | 3))
a
b
c


a b c (1 | 2 | 3)


Alternation
Sequence


array(a b c)

array(a b c 1, a b c 2, a b c 3)


(a | b ) (1 | 2 | 3 | (x | y | z) | 4)


array(a, b)
array(1, 2, 3)
array()
array(x, y, z)

array

(a | b ) (1 | 2 | 3 | p q r (x | y | z) | 4)
(a | b ) (1 | 2 | 3 | (p q r x | p q r y | p q r z) | 4)
(a | b ) (1 | 2 | 3 | p q r x | p q r y | p q r z | 4)



p q r (x | y | {1 | 2})

p q r (x | y | 1 | 2

**/
