<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\ResultInterface;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\SequenceResult;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class OperationFactory implements NormalizeOperationFactory
{
    /**
     * @param array $resultItems
     * @return SequenceResult
     */
    public function createResult(array $resultItems)
    {
        return new SequenceResult($resultItems);
    }

    /**
     * @param array|Expression[] $operandItems
     * @return Operand
     */
    public function createOperand(array $operandItems)
    {
        return new Operand($operandItems);
    }

    /**
     * @return Operator
     */
    public function createOperator()
    {
        return new Operator();
    }
}
