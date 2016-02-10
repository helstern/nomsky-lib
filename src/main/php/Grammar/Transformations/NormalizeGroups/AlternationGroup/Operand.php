<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\AlternationGroup;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperand;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperator;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\ResultInterface;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\PerformNormalizeOperationStrategy;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class Operand implements NormalizeOperand
{
    /** @var Expression[] */
    protected $alternationItems;

    /**
     * @param array|Expression[] $alternationItems
     */
    public function __construct(array $alternationItems)
    {
        $this->alternationItems = $alternationItems;
    }

    /**
     * @return Expression[]
     */
    public function getItems()
    {
        return $this->alternationItems;
    }

    /**
     * @param NormalizeOperand $rightOperand
     * @return PerformOperationStrategy
     */
    public function createOperationStrategy(NormalizeOperand $rightOperand)
    {
        return new PerformOperationStrategy($this->getItems(), $rightOperand->getItems());
    }

    /**
     * @param PerformNormalizeOperationStrategy $strategy
     * @param NormalizeOperator $operator
     * @return ResultInterface
     */
    public function performOperation(PerformNormalizeOperationStrategy $strategy, NormalizeOperator $operator)
    {
        return $strategy->performRightOperandIsAlternation($operator);
    }
}
