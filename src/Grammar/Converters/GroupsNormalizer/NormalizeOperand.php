<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult\ResultInterface;

interface NormalizeOperand
{
    /**
     * @return array
     */
    public function getItems();

    /**
     * @param NormalizeOperand $rightOperand
     * @return PerformNormalizeOperationStrategy
     */
    public function createOperation(NormalizeOperand $rightOperand);

    /**
     * @param PerformNormalizeOperationStrategy $strategy
     * @param NormalizeOperator $operator
     * @return ResultInterface
     */
    public function performOperation(PerformNormalizeOperationStrategy $strategy, NormalizeOperator $operator);
}
