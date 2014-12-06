<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer;

use Helstern\Nomsky\Grammar\Expressions\Expression;

interface NormalizeOperationFactory
{
    /**
     * @param array|Expression[] $operandItems
     * @return NormalizeOperand
     */
    public function createOperand(array $operandItems);

    /**
     * @return NormalizeOperator
     */
    public function createOperator();
}
