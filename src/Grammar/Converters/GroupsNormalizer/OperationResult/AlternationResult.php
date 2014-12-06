<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\AlternationGroup\Operand;

class AlternationResult implements ResultInterface
{
    /** @var array|Expression[] */
    protected $alternationItems;

    /**
     * @param array|Expression[] $alternationItems
     */
    public function __construct(array $alternationItems)
    {
        $this->alternationItems = $alternationItems;
    }

    /**
     * @return Alternation
     */
    public function toExpression()
    {
        return new Alternation(reset($this->alternationItems), array_slice($this->alternationItems, 1));
    }

    /**
     * @return Operand
     */
    public function toOperand()
    {
        return new Operand($this->alternationItems);
    }
}
