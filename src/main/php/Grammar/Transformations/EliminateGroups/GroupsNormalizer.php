<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperand;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperator;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\ResultInterface;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

class GroupsNormalizer
{
    /** @var NormalizeOperationFactory */
    protected $operationFactory;

    /**
     * @param NormalizeOperationFactory $operationFactory
     */
    public function __construct(NormalizeOperationFactory $operationFactory)
    {
        $this->operationFactory = $operationFactory;
    }

    /**
     * @param array $operandItems
     * @return NormalizeOperand
     */
    protected function createOperand(array $operandItems)
    {
        return $this->operationFactory->createOperand($operandItems);
    }

    /**
     * @return NormalizeOperator
     */
    protected function createNormalizeOperator()
    {
        return $this->operationFactory->createOperator();
    }

    /**
     * @param Expression[]|\SplStack $childrenStack
     * @param NormalizeOperand[]|\SplStack $normalizeOperands
     * @return ResultInterface
     */
    public function eliminateGroups(\SplStack $childrenStack, \SplStack $normalizeOperands)
    {
        do {
            /** @var NormalizeOperand $rightOperand */
            $rightOperand = null;
            /** @var NormalizeOperand $leftOperand */
            $leftOperand = null;
            /** @var Expression[]|array $head */
            $head = array();

            $child = $childrenStack->pop(); //array_shift($childrenStack);
            while (false == is_null($child) && false == $child instanceof Group) {
                $head[] = $child;

                $child = $childrenStack->isEmpty() ? null : $childrenStack->pop(); //array_shift($expressionChildren);
            }

            if (!empty($head)) {
                $leftOperand          = $this->createOperand($head);
                $rightOperand         = $normalizeOperands->pop(); //array_pop($normalizeOperands);
                $result = $this->performOperation($leftOperand, $rightOperand);

                $this->pushResultToStacks($result, $childrenStack, $normalizeOperands);

                continue;
            } elseif (empty($childrenStack)) {
                continue;
            }

            $tail = array();
            $child = $childrenStack->pop(); //array_shift($childrenStack);
            while (false === is_null($child) && false == $child instanceof Group) {
                $tail[] = $child;

                $child = $childrenStack->isEmpty() ? null : $childrenStack->pop(); //array_shift($expressionChildren);
            }

            if (empty($tail)) {
                $leftOperand    = $normalizeOperands->pop(); //array_pop($normalizeOperands);
                $rightOperand   = $normalizeOperands->pop(); //array_pop($normalizeOperands);
                $result = $this->performOperation($leftOperand, $rightOperand);

                $this->pushResultToStacks($result, $childrenStack, $normalizeOperands);
            } else {
                $leftOperand    = $normalizeOperands->pop(); //array_pop($normalizeOperands);
                $rightOperand   = $this->createOperand($tail);
                $result = $this->performOperation($leftOperand, $rightOperand);

                $this->pushResultToStacks($result, $childrenStack, $normalizeOperands);
            }
        } while (! $childrenStack->isEmpty());

        return $result;
    }

    /**
     * @param ResultInterface $result
     * @param \SplStack $childrenStack
     * @param \SplStack $operandsStack
     */
    protected function pushResultToStacks(ResultInterface $result, \SplStack $childrenStack, \SplStack $operandsStack)
    {
        if ($childrenStack->isEmpty()) {
            return;
        }

        $newGroup = new Group($result->toExpression());
        $childrenStack->push($newGroup); //array_push($childrenStack, $newGroup);

        $operandsStack->push($result->toOperand()); //array_push($normalizeOperands, $result->toOperand());
    }

    /**
     * @param NormalizeOperand $leftOperand
     * @param NormalizeOperand $rightOperand
     * @return ResultInterface
     */
    protected function performOperation(NormalizeOperand $leftOperand, NormalizeOperand $rightOperand)
    {
        $operator = $this->createNormalizeOperator();

        $performOperationStrategy = $leftOperand->createOperationStrategy($rightOperand);
        $result = $rightOperand->performOperation($performOperationStrategy, $operator);

        return $result;
    }
}