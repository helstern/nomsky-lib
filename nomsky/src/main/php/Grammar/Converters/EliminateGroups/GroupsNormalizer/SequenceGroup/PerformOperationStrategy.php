<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\SequenceGroup;

use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\NormalizeOperator;
use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\NormalizeOperand;
use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\PerformNormalizeOperationStrategy;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class PerformOperationStrategy implements PerformNormalizeOperationStrategy
{
    /** @var array|Expression[] */
    protected $leftSequenceItems;

    /** @var array|Expression[] */
    protected $rightOperandItems;

    /**
     * @param array|Expression[] $leftSequenceItems
     * @param array|Expression[] $rightOperandItems
     */
    public function __construct(array $leftSequenceItems, array $rightOperandItems)
    {
        $this->leftSequenceItems = $leftSequenceItems;
        $this->rightOperandItems           = $rightOperandItems;
    }

    public function isLeftOperandSameAs(NormalizeOperand $leftOperand)
    {
        return $leftOperand instanceof Operand;
    }

    public function performRightOperandIsAlternation(NormalizeOperator $operator)
    {
        return $operator->operateOnSequenceAndAlternation(
            $this->leftSequenceItems,
            $this->rightOperandItems
        );
    }

    public function performRightOperandIsSequence(NormalizeOperator $operator)
    {
        return $operator->operateOnSequenceAndSequence(
            $this->leftSequenceItems,
            $this->rightOperandItems
        );
    }
}
