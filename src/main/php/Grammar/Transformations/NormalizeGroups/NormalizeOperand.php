<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\ResultInterface;

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
