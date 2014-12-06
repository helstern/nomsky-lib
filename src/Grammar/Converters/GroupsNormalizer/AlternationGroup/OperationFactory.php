<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\AlternationGroup;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperand;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class OperationFactory implements NormalizeOperationFactory
{
    /**
     * @param array|Expression[] $operandItems
     * @return NormalizeOperand
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
