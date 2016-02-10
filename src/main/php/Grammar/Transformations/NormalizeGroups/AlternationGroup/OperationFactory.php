<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\AlternationGroup;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperationFactory;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperand;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\AlternationResult;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class OperationFactory implements NormalizeOperationFactory
{
    /**
     * @param array $resultItems
     * @return AlternationResult
     */
    public function createResult(array $resultItems)
    {
        return new AlternationResult($resultItems);
    }

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
