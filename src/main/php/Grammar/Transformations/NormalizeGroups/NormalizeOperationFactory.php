<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\ResultInterface;
use Helstern\Nomsky\Grammar\Expressions\Expression;

interface NormalizeOperationFactory
{
    /**
     * @param array $resultItems
     * @return ResultInterface
     */
    public function createResult(array $resultItems);

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
