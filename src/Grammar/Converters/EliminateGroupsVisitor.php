<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\GroupsElimination;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperand;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup\OperationFactory as SequenceGroupOperationFactory;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\AlternationGroup\OperationFactory as AlternationGroupOperationFactory;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

class EliminateGroupsVisitor extends AbstractErrorTriggeringVisitor implements HierarchyVisitor
{
    /** @var Expression */
    protected $root;

    /** @var array[] */
    protected $stackOfNormalizeOperands;

    /** @var array|Expression[] */
    protected $stackOfParents;

    /** @var array|NormalizeOperationFactory[] */
    protected $stackOfNormalizeOperationFactories;

    /** @var array[] */
    protected $stackOfChildExpressions = array();

//    /** @var array|ExpressionStackPushStrategy  */
//    protected $stackPushStrategiesStack = array();

    /**
     * @return Expression|null
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param Expression $e
     * @param NormalizeOperationFactory $operationFactory
     */
    protected  function onBeforeStartVisitExpressionIterable(Expression $e, NormalizeOperationFactory $operationFactory)
    {
//        $stackPushStrategy = new ExpressionStackPushStrategy($this->parentIsGroup);
//        array_push($this->stackPushStrategiesStack, $stackPushStrategy);

        array_push($this->stackOfParents, $e);
        array_push($this->stackOfNormalizeOperationFactories, $operationFactory);

        $this->stackOfChildExpressions[]    = array();
    }

    protected function onAfterEndVisitExpressionIterable(Expression $e)
    {
        //remove self from stack of parents
        array_pop($this->stackOfParents);

        /** @var $parent Expression */
        $parent = count($this->stackOfParents) ? end($this->stackOfParents) : null;

        /** @var array|Expression[] $children */
        $children               = array_pop($this->stackOfChildExpressions);

        /** @var  $normalizeOperands */
        $normalizeOperands      = array_pop($this->stackOfNormalizeOperands);

        /** @var NormalizeOperationFactory $normalizeOperationFactory */
        $normalizeOperationFactory = array_pop($this->stackOfNormalizeOperationFactories);

        if (empty($normalizeOperands)) {
            if ($parent instanceof Group) {
                $grandParentChildren    = array_pop($this->stackOfChildExpressions);
                array_push($grandParentChildren, new Group($e));
                array_push($this->stackOfChildExpressions, $grandParentChildren);

                $grandParentOperands    = array_pop($this->stackOfNormalizeOperands);
                array_push($grandParentOperands, $normalizeOperationFactory->createOperand($children));
                array_push($this->stackOfNormalizeOperands, $grandParentOperands);
            } else {
                $newExpression = $e;

                $grandParentChildren    = array_pop($this->stackOfChildExpressions);
                array_push($grandParentChildren, $newExpression);
                array_push($this->stackOfChildExpressions, $grandParentChildren);
            }
        } else {
            $childrenStack = new \SplStack();
            while (count($children)) {
                $childrenStack->push(array_pop($children));
            }

            $operandsStack = new \SplStack();
            while (count($normalizeOperands)) {
                $operandsStack->push(array_pop($normalizeOperands));
            }

            $groupsElimination = new GroupsElimination($normalizeOperationFactory);
            $eliminationResult = $groupsElimination->eliminateGroups($childrenStack, $operandsStack);

            if ($parent instanceof Group) {
                $grandParentChildren    = array_pop($this->stackOfChildExpressions);
                array_push($grandParentChildren, $eliminationResult->toGroup());
                array_push($this->stackOfChildExpressions, $grandParentChildren);

                $grandParentOperands    = array_pop($this->stackOfNormalizeOperands);
                array_push($grandParentOperands, $eliminationResult->toOperand());
                array_push($this->stackOfNormalizeOperands, $grandParentOperands);
            } else {
                $newExpression = $eliminationResult->toExpression();

                $grandParentChildren    = array_pop($this->stackOfChildExpressions);
                array_push($grandParentChildren, $newExpression);
                array_push($this->stackOfChildExpressions, $grandParentChildren);
            }
        }
    }

    /**
     * @param Expression $e
     */
    protected function onAfterEndVisitExpressionIterable__(Expression $e)
    {
//        $stackPushStrategy      = array_pop($this->stackPushStrategiesStack);
//        /** @var Combinator $c */
//        $c                      = array_pop($this->stackOfCombinators);
//
//        /** @var array|Expression[] $children */
//        $children               = array_pop($this->stackOfChildExpressions);
//        /** @var array|Combinator[] $childGroupCombinators */
//        $childGroupCombinators  = array_pop($this->stackOfChildGroupCombinators);
//
//        if (empty($childGroupCombinators)) {
//            $childExpression = $e;
//            $childCombinator = $c;
//        } else {
//            $builder = new ExpressionCombinatorBuilder();
//            $endVisitExpressionHandler = new ExpressionGroupsRemover($c);
//            $endVisitExpressionHandler->removeGroups($children, $childGroupCombinators, $builder);
//
//            $childExpression = $builder->build();
//            $childCombinator = $builder->getCombinator();
//        }
//
//        $newChildStack = $stackPushStrategy->pushChild($childExpression, $this->stackOfChildExpressions);
//        if (is_null($newChildStack)) {
//            $this->root = $childExpression;
//        } else {
//            $this->stackOfChildExpressions = $newChildStack;
//        }
//
//        $newCombinatorStack = $stackPushStrategy->pushCombinator($childCombinator, $this->stackOfChildGroupCombinators);
//        if (!is_null($newCombinatorStack)) {
//            $this->stackOfChildGroupCombinators = $newCombinatorStack;
//        }
    }

    public function startVisitAlternation(Alternation $expression)
    {
        $normalizeOperationFactory = new AlternationGroupOperationFactory();
        $this->onBeforeStartVisitExpressionIterable($expression, $normalizeOperationFactory);

        return true;
    }

    public function endVisitAlternation(Alternation $expression)
    {
        $this->onAfterEndVisitExpressionIterable($expression);

        return true;
    }

    public function startVisitSequence(Sequence $expression)
    {
        $normalizeOperationFactory = new SequenceGroupOperationFactory();
        $this->onBeforeStartVisitExpressionIterable($expression, $normalizeOperationFactory);

        return true;
    }

    public function endVisitSequence(Sequence $expression)
    {
        $this->onAfterEndVisitExpressionIterable($expression);

        return true;
    }

    public function startVisitGroup(Group $expression)
    {
        array_push($this->stackOfParents, $expression);
        return true;
    }

    public function endVisitGroup(Group $expression)
    {
        //transfer the children of the group to the group's parent
        array_pop($this->stackOfParents);
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
