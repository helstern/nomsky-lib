<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

class EliminateGroupsVisitor extends AbstractErrorTriggeringVisitor implements HierarchyVisitor
{
    /** @var Expression */
    protected $root;

    /** @var array|Combinator[] */
    protected $stackOfCombinators = array();

    /** @var array[] */
    protected $stackOfChildExpressions = array();

    /** @var array[] */
    protected $stackOfChildGroupCombinators = array();

    /** @var array|ExpressionStackPushStrategy  */
    protected $stackPushStrategiesStack = array();

    /** @var bool */
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
     * @param Combinator $c
     */
    protected  function onBeforeStartVisitExpressionIterable(Expression $e, Combinator $c)
    {
        $stackPushStrategy = new ExpressionStackPushStrategy($this->parentIsGroup);
        array_push($this->stackPushStrategiesStack, $stackPushStrategy);

        array_push($this->stackOfCombinators, $c);

        $this->stackOfChildExpressions[]    = array();
        $this->stackOfChildGroupCombinators[] = array();
    }

    /**
     * @param Expression $e
     */
    protected function onAfterEndVisitExpressionIterable(Expression $e)
    {
        $stackPushStrategy      = array_pop($this->stackPushStrategiesStack);
        /** @var Combinator $c */
        $c                      = array_pop($this->stackOfCombinators);

        /** @var array|Expression[] $children */
        $children               = array_pop($this->stackOfChildExpressions);
        /** @var array|Combinator[] $childGroupCombinators */
        $childGroupCombinators  = array_pop($this->stackOfChildGroupCombinators);

        if (empty($childGroupCombinators)) {
            $childExpression = $e;
            $childCombinator = $c;
        } else {
            $builder = new ExpressionCombinatorBuilder();
            $endVisitExpressionHandler = new ExpressionGroupsRemover($c);
            $endVisitExpressionHandler->removeGroups($children, $childGroupCombinators, $builder);

            $childExpression = $builder->build();
            $childCombinator = $builder->getCombinator();
        }

        $newChildStack = $stackPushStrategy->pushChild($childExpression, $this->stackOfChildExpressions);
        if (is_null($newChildStack)) {
            $this->root = $childExpression;
        } else {
            $this->stackOfChildExpressions = $newChildStack;
        }

        $newCombinatorStack = $stackPushStrategy->pushCombinator($childCombinator, $this->stackOfChildGroupCombinators);
        if (!is_null($newCombinatorStack)) {
            $this->stackOfChildGroupCombinators = $newCombinatorStack;
        }
    }

    public function startVisitAlternation(Alternation $expression)
    {
        $combinator                 = new CombinatorForAlternation();
        $this->onBeforeStartVisitExpressionIterable($expression, $combinator);

        if ($this->parentIsGroup) {
            $this->parentIsGroup        = false;
        }

        return true;
    }

    public function endVisitAlternation(Alternation $expression)
    {
        $this->onAfterEndVisitExpressionIterable($expression);

        return true;
    }

    public function startVisitSequence(Sequence $expression)
    {
        $combinator                 = new CombinatorForSequence();
        $this->onBeforeStartVisitExpressionIterable($expression, $combinator);

        if ($this->parentIsGroup) {
            $this->parentIsGroup = false;
        }

        return true;
    }

    public function endVisitSequence(Sequence $expression)
    {
        $this->onAfterEndVisitExpressionIterable($expression);

        return true;
    }

    public function startVisitGroup(Group $expression)
    {
        $this->parentIsGroup        = true;

        return true;
    }

    public function endVisitGroup(Group $expression)
    {
        //transfer the children of the group to the group's parent

        return true;
    }

    public function visitExpression(Expression $expression)
    {
        /** @var array $lastListOfSymbols */
        $lastListOfSymbols = array_pop($this->stackOfChildExpressions);
        $lastListOfSymbols[] = $expression;
        array_push($this->stackOfChildExpressions, $lastListOfSymbols);

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
