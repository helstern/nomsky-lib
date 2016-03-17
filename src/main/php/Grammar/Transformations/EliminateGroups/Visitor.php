<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

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

    /** @var SplStack */
    private $stackOfGroups;

    /** @var SplStack */
    private $stackOfEliminators;

    /** @var GroupedChoiceEliminator */
    private $choiceEliminator;

    /** @var GroupedConcatenationEliminator */
    private $concatenationEliminator;

    /**
     * @param GroupedChoiceEliminator $choiceEliminator
     * @param GroupedConcatenationEliminator $concatenationEliminator
     */
    public function __construct(
        GroupedChoiceEliminator $choiceEliminator = null,
        GroupedConcatenationEliminator $concatenationEliminator = null
    ) {
        $this->stackOfChildren = new SplStack();
        $this->stackOfGroups = new SplStack();
        $this->stackOfParents = new SplStack();
        $this->stackOfEliminators = new SplStack();

        if (is_null($choiceEliminator)) {
            $this->choiceEliminator = new GroupedChoiceEliminator();
        } else {
            $this->choiceEliminator = $choiceEliminator;
        }

        if (is_null($concatenationEliminator)) {
            $this->concatenationEliminator = new GroupedConcatenationEliminator();
        } else {
            $this->concatenationEliminator = $concatenationEliminator;
        }
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
        $this->stackOfGroups->push([]);

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

        //check if we have some groups that we need to eliminate
        $newChildren = null;
        $groups  = $this->stackOfGroups->pop();
        if (count($groups)) {
            $children = $this->stackOfChildren->pop();
            $newChildren = $this->eliminateGroupsFromChoice($children, $groups);
        }

        $newExpression = null;
        if (is_null($newChildren)) {
            //no groups in this expression so we recreate it
            $children = $this->stackOfChildren->pop();
            $newExpression = new Expressions\Choice(array_shift($children), $children);
            $this->tryPushGroupEliminator($this->choiceEliminator, $parent, $newExpression);
        } elseif (is_array($newChildren) && 1 == count($newChildren)) {
            //only one expression in the children list so we use it to replace the original
            $newExpression = $newChildren[0];
            $this->tryPushGroupEliminator($this->concatenationEliminator, $parent, $newExpression);
        } else {
            //there are multiple expression in the children list so we create a choice
            $newExpression = new Expressions\Choice(array_shift($newChildren), $newChildren);
            $this->tryPushGroupEliminator($this->choiceEliminator, $parent, $newExpression);
        }

        if (is_null($parent)) {
            $this->root = $newExpression;
        } else {
            /** @var array $lastListOfSymbols */
            $siblings = $this->stackOfChildren->pop();
            $siblings[] = $newExpression;
            $this->stackOfChildren->push($siblings);
        }

        return true;
    }

    /**
     * Push group eliminator $eliminator of $child into the group eliminators stack if $parent is a Group
     *
     * @param \Helstern\Nomsky\Grammar\Transformations\EliminateGroups\GroupedExpressionEliminator $eliminator
     * @param Expressions\Expression|null $parent
     * @param Expressions\Expression $child
     *
     * @return bool
     */
    private function tryPushGroupEliminator(GroupedExpressionEliminator $eliminator, $parent, $child)
    {
        // add the group elimination strategy if this is a grouped choice
        if ($parent instanceof Expressions\Group) {
            $this->stackOfEliminators->push($eliminator);
            return true;
        }

        return false;
    }

    public function startVisitConcatenation(Expressions\Concatenation $expression)
    {
        $this->stackOfChildren->push([]);
        $this->stackOfGroups->push([]);
        $this->stackOfParents->push($expression);
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

        //check if we have some groups that we need to eliminate
        $newChildren = null;
        $groups  = $this->stackOfGroups->pop();
        if (count($groups)) {
            $children = $this->stackOfChildren->pop();
            $newChildren = $this->eliminateGroupsFromConcatenation($children, $groups);
        }

        $newExpression = null;
        if (is_null($newChildren)) {
            //no groups in this expression so we recreate it
            $children = $this->stackOfChildren->pop();
            $newExpression = new Expressions\Concatenation(array_shift($children), $children);
            $this->tryPushGroupEliminator($this->concatenationEliminator, $parent, $newExpression);
        } elseif (is_array($newChildren) && 1 == count($newChildren)) {
            $newExpression = $newChildren[0];
            $this->tryPushGroupEliminator($this->concatenationEliminator, $parent, $newExpression);
        } else {
            //there are multiple expression in the children list so we create a choice
            $newExpression = new Expressions\Choice(array_shift($newChildren), $newChildren);
            $this->tryPushGroupEliminator($this->choiceEliminator, $parent, $newExpression);
        }

        if (is_null($parent)) {
            $this->root = $newExpression;
        } else {
            /** @var array $lastListOfSymbols */
            $siblings = $this->stackOfChildren->pop();
            $siblings[] = $newExpression;
            $this->stackOfChildren->push($siblings);
        }

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
        /** @var GroupedExpressionEliminator $eliminator */
        $eliminator = $this->stackOfEliminators->pop();

        //group is the root of the tree
        if (0 == $this->stackOfChildren->count()) {
            $this->root = $expression;
            return true;
        }

        // add the group to the list of siblings
        $newGroup = new Expressions\Group($expression);
        $siblings = $this->stackOfChildren->pop();
        $siblings[] = $newGroup;
        $this->stackOfChildren->push($siblings);

        // add the group elimination strategy to the list of eliminators
        $groupEliminators = $this->stackOfGroups->pop();
        $groupEliminators[] = $eliminator;
        $this->stackOfGroups->push($groupEliminators);

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
