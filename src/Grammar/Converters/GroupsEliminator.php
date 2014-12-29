<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\GroupsElimination;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup\OperationFactory as SequenceGroupOperationFactory;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\AlternationGroup\OperationFactory as AlternationGroupOperationFactory;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

class GroupsEliminator extends AbstractErrorTriggeringVisitor implements HierarchyVisitor
{
    /** @var Expression */
    protected $root;

    /** @var array|Expression[] */
    protected $stackOfParents = array();

    /** @var array[] */
    protected $stackOfNormalizeOperands = array();

    /** @var array|NormalizeOperationFactory[] */
    protected $stackOfNormalizeOperationFactories = array();

    /** @var array[] */
    protected $stackOfChildren = array();

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
        array_push($this->stackOfParents, $e);
        array_push($this->stackOfNormalizeOperationFactories, $operationFactory);

        $this->stackOfChildren[]    = array();
        $this->stackOfNormalizeOperands[] = array();
    }

    protected function onAfterEndVisitExpressionIterable(Expression $e)
    {
        //remove $e from stack of parents
        array_pop($this->stackOfParents);

        /** @var $parent Expression */
        $parent = count($this->stackOfParents) ? end($this->stackOfParents) : null;

        /** @var array|Expression[] $children */
        $children               = array_pop($this->stackOfChildren);

        /** @var  $normalizeOperands */
        $normalizeOperands      = array_pop($this->stackOfNormalizeOperands);

        /** @var NormalizeOperationFactory $normalizeOperationFactory */
        $normalizeOperationFactory = array_pop($this->stackOfNormalizeOperationFactories);

        if (empty($normalizeOperands)) {
            if ($parent instanceof Group) {
                $grandParentChildren    = array_pop($this->stackOfChildren);
                array_push($grandParentChildren, new Group($e));
                array_push($this->stackOfChildren, $grandParentChildren);

                $grandParentOperands    = array_pop($this->stackOfNormalizeOperands);
                array_push($grandParentOperands, $normalizeOperationFactory->createOperand($children));
                array_push($this->stackOfNormalizeOperands, $grandParentOperands);
            } else if (is_null($parent)) {
                $this->root = $normalizeOperationFactory->createResult($children)->toExpression();
            } else {
                $newChild = $normalizeOperationFactory->createResult($children)->toExpression();

                $grandParentChildren    = array_pop($this->stackOfChildren);
                array_push($grandParentChildren, $newChild);
                array_push($this->stackOfChildren, $grandParentChildren);
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
                $grandParentChildren    = array_pop($this->stackOfChildren);
                array_push($grandParentChildren, $eliminationResult->toGroup());
                array_push($this->stackOfChildren, $grandParentChildren);

                $grandParentOperands    = array_pop($this->stackOfNormalizeOperands);
                array_push($grandParentOperands, $eliminationResult->toOperand());
                array_push($this->stackOfNormalizeOperands, $grandParentOperands);
            } else if (is_null($parent)) {
                $this->root = $eliminationResult->toExpression();
            } else {
                $newChild = $eliminationResult->toExpression();

                $grandParentChildren    = array_pop($this->stackOfChildren);
                array_push($grandParentChildren, $newChild);
                array_push($this->stackOfChildren, $grandParentChildren);
            }
        }
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
