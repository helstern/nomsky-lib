<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateNesting;

use Helstern\Nomsky\Grammar\Converter\AbstractErrorTriggeringVisitor;
use Helstern\Nomsky\Grammar\Expressions;
use SplStack;

class Visitor extends AbstractErrorTriggeringVisitor implements Expressions\Visitor\HierarchyVisitor
{
    /** @var Expressions\Expression */
    private $root;

    /** @var SplStack */
    private $stackOfParents;

    /** @var SplStack */
    private $stackOfChildren;

    public function __construct() {
        $this->stackOfParents = new SplStack();
        $this->stackOfChildren = new SplStack();
    }

    /**
     * @return Expressions\Expression|null
     */
    public function getRoot()
    {
        return $this->root;
    }

    public function startVisitChoice(Expressions\Choice $expression)
    {
        $this->stackOfParents->push($expression);
        $this->stackOfChildren->push([]);

        return true;
    }

    public function endVisitChoice(Expressions\Choice $expression)
    {
        //remove self from stack of parents
        $this->stackOfParents->pop();

        // get a reference to its parent
        $parent = null;
        if ($this->stackOfParents->count()) {
            $parent = $this->stackOfParents->top();
        }

        $children = $this->stackOfChildren->pop();
        if (is_null($parent)) {
            $this->root = new Expressions\Choice(array_shift($children), $children);
            return true;
        }

        //merge with parent
        if ($parent instanceof Expressions\Choice) {
            $siblings = $this->stackOfChildren->pop();
            foreach ($children as $child) {
                $siblings[] = $child;
            }
            $this->stackOfChildren->push($siblings);
            return true;
        }

        //re-create
        $siblings = $this->stackOfChildren->pop();
        $siblings[] = new Expressions\Choice(array_shift($children), $children);
        $this->stackOfChildren->push($siblings);

        return true;
    }

    public function startVisitConcatenation(Expressions\Concatenation $expression)
    {
        $this->stackOfParents->push($expression);
        $this->stackOfChildren->push([]);
        return true;
    }

    public function endVisitConcatenation(Expressions\Concatenation $expression)
    {
        //remove self from stack of parents
        $this->stackOfParents->pop();

        // get a reference to its parent
        $parent = null;
        if ($this->stackOfParents->count()) {
            $parent = $this->stackOfParents->top();
        }

        $children = $this->stackOfChildren->pop();
        //is root
        if (is_null($parent)) {
            $this->root = new Expressions\Concatenation(array_shift($children), $children);
            return true;
        }

        //merge with parent
        if ($parent instanceof Expressions\Concatenation) {
            $siblings = $this->stackOfChildren->pop();
            foreach ($children as $child) {
                $siblings[] = $child;
            }
            $this->stackOfChildren->push($siblings);
            return true;
        }

        //re-create
        $siblings = $this->stackOfChildren->pop();
        $siblings[] = new Expressions\Concatenation(array_shift($children), $children);
        $this->stackOfChildren->push($siblings);

        return true;
    }

    public function startVisitGroup(Expressions\Group $expression)
    {
        $this->stackOfChildren->push([]);
        $this->stackOfParents->push($expression);
    }

    public function endVisitGroup(Expressions\Group $expression)
    {
        $this->stackOfParents->pop();
        $children = $this->stackOfChildren->pop();
        $expression = array_pop($children);

        //group is the root of the tree
        if (0 == $this->stackOfChildren->count()) {
            $this->root = new Expressions\Group($expression);
            return true;
        }

        // add the group to the list of siblings
        $siblings = $this->stackOfChildren->pop();
        $siblings[] = new Expressions\Group($expression);
        $this->stackOfChildren->push($siblings);

        return true;
    }

    public function visitExpression(Expressions\Expression $expression)
    {
        if (0 == $this->stackOfParents->count()) {
            $this->root = $expression;
            return true;
        }

        /** @var array $lastListOfSymbols */
        $siblings = $this->stackOfChildren->pop();
        $siblings[] = $expression;
        $this->stackOfChildren->push($siblings);

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

    /**
     * Eliminate all groups from Concatenation
     *
     * @param array|Expressions\Expression[] $expressions
     * @param array|GroupedExpressionEliminator[] $eliminators
     *
     * @return Expressions\Expression[]
     */
    private function eliminateGroupsFromConcatenation(array $expressions, array $eliminators)
    {
        $list = [];
        $list[] = new Expressions\Concatenation(array_shift($expressions), $expressions);
        $cleaned = new \ArrayObject();
        foreach ($eliminators as $eliminator) {
            foreach ($list as $expression) {
                $eliminator->removeFromConcatenation($expression, $cleaned);
            }
           $list = $cleaned->exchangeArray([]);
       }

        return $list;

    }

    /**
     * Eliminate all groups from a Choice
     *
     * @param array|Expressions\Expression[] $expressions
     * @param array|GroupedExpressionEliminator[] $eliminators
     *
     * @return Expressions\Expression[]
     */
    private function eliminateGroupsFromChoice(array $expressions, array $eliminators)
    {
        $cleaned = new \ArrayObject();
        do {
            $eliminator = array_shift($eliminators);

            $expression = array_shift($expressions);
            while (! $expression instanceof Expressions\Group) {
                $cleaned->append($expression);
                $expression = array_shift($expressions);
            }

            $eliminator->removeGroup($expression, $cleaned);
        } while (! is_null(key($eliminators)));

        while (! is_null(key($expressions))) {
            $expression = array_shift($expressions);
            $cleaned->append($expression);
        }

        $list = $cleaned->exchangeArray([]);
        return $list;
    }
}
