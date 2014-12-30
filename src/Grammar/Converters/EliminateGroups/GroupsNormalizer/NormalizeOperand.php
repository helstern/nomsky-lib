<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer;

use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\OperationResult\ResultInterface;

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
    public function createOperationStrategy(NormalizeOperand $rightOperand);

    /**
     * @param PerformNormalizeOperationStrategy $strategy
     * @param NormalizeOperator $operator
     * @return ResultInterface
     */
    public function performOperation(PerformNormalizeOperationStrategy $strategy, NormalizeOperator $operator);
}
