<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\SequenceGroup;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperand;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperator;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\ResultInterface;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\PerformNormalizeOperationStrategy;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class Operand implements NormalizeOperand
{
    /** @var Expression[] */
    protected $sequenceItems;

    /**
     * @param array|Expression[] $sequenceItems
     */
    public function __construct(array $sequenceItems)
    {
        $this->sequenceItems = $sequenceItems;
    }

    /**
     * @return array|Expression[]
     */
    public function getItems()
    {
        return $this->sequenceItems;
    }

    /**
     * @param NormalizeOperand $rightOperand
     * @return PerformOperationStrategy
     */
    public function createOperationStrategy(NormalizeOperand $rightOperand)
    {
        return new PerformOperationStrategy($this->getItems(), $rightOperand->getItems());
    }

    /**
     * @param PerformNormalizeOperationStrategy $strategy
     * @param NormalizeOperator $operator
     * @return ResultInterface
     */
    public function performOperation(PerformNormalizeOperationStrategy $strategy, NormalizeOperator $operator)
    {
        return $strategy->performRightOperandIsSequence($operator);
    }
}
