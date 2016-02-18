<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\ConcatenationGroup\Operand;

class SequenceResult implements ResultInterface
{
    /** @var array|Expression[] */
    protected $sequenceItems;

    /**
     * @param array|Expression[] $sequenceItems
     */
    public function __construct(array $sequenceItems)
    {
        $this->sequenceItems = $sequenceItems;
    }

    /**
     * @return Group
     */
    public function toGroup()
    {
        $expression = $this->toExpression();
        return new Group($expression);
    }

    /**
     * @return Concatenation
     */
    public function toExpression()
    {
        return new Concatenation(reset($this->sequenceItems), array_slice($this->sequenceItems, 1));
    }

    /**
     * @return Operand
     */
    public function toOperand()
    {
        return new Operand($this->sequenceItems);
    }
}
