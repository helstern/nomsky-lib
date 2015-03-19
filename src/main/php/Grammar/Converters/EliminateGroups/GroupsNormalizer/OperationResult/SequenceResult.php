<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\SequenceGroup\Operand;

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
     * @return Sequence
     */
    public function toExpression()
    {
        return new Sequence(reset($this->sequenceItems), array_slice($this->sequenceItems, 1));
    }

    /**
     * @return Operand
     */
    public function toOperand()
    {
        return new Operand($this->sequenceItems);
    }
}
