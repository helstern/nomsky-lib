<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\ResultInterface;
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
