<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\AlternationGroup\Operand;
use Helstern\Nomsky\Grammar\Expressions\Group;

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
     * @return Group
     */
    public function toGroup()
    {
        $expression = $this->toExpression();
        return new Group($expression);
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
