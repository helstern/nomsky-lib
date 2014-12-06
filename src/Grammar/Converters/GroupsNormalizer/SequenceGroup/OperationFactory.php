<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class OperationFactory implements NormalizeOperationFactory
{
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
