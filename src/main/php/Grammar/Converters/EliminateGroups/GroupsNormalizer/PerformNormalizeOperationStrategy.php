<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer;

use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\OperationResult\ResultInterface;

interface PerformNormalizeOperationStrategy
{
    /**
     * @param NormalizeOperand $otherOperand
     * @return bool
     */
    public function isLeftOperandSameAs(NormalizeOperand $otherOperand);

    /**
     * @param NormalizeOperator $operator
     * @return ResultInterface
     */
    public function performRightOperandIsAlternation(NormalizeOperator $operator);

    /**
     * @param NormalizeOperator $operator
     * @return ResultInterface
     */
    public function performRightOperandIsSequence(NormalizeOperator $operator);
}
