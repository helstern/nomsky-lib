<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperand;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperator;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\ResultInterface;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\PerformNormalizeOperationStrategy;
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
    public function createOperation(NormalizeOperand $rightOperand)
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
