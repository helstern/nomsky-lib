<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\SequenceGroup\Operand;

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
