<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\AlternationGroup;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperand;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\NormalizeOperator;
use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\PerformNormalizeOperationStrategy;
use Helstern\Nomsky\Grammar\Expressions\Expression;

class PerformOperationStrategy implements PerformNormalizeOperationStrategy
{
    /** @var array|Expression[] */
    protected $leftAlternationItems;

    /** @var array|Expression[] */
    protected $rightItems;

    /**
     * @param array|Expression[] $leftAlternationItems
     * @param array|Expression[] $rightItems
     */
    public function __construct(array $leftAlternationItems, array $rightItems)
    {
        $this->leftAlternationItems = $leftAlternationItems;
        $this->rightItems           = $rightItems;
    }

    public function isLeftOperandSameAs(NormalizeOperand $leftOperand)
    {
        return $leftOperand instanceof Operand;
    }

    public function performRightOperandIsAlternation(NormalizeOperator $operator)
    {
        return $operator->operateOnAlternationAndAlternation(
            $this->leftAlternationItems,
            $this->rightItems
        );
    }

    public function performRightOperandIsSequence(NormalizeOperator $operator)
    {
        return $operator->operateOnAlternationAndSequence(
            $this->leftAlternationItems,
            $this->rightItems
        );
    }
}
