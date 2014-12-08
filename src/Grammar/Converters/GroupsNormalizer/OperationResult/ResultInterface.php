<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperand;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

interface ResultInterface
{
    /**
     * @return Group
     */
    public function toGroup();

    /**
     * @return Expression
     */
    public function toExpression();

    /**
     * @return NormalizeOperand
     */
    public function toOperand();
}
