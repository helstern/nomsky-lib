<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Converters\GroupsNormalizer\NormalizeOperand;
use Helstern\Nomsky\Grammar\Expressions\Expression;

interface ResultInterface
{
    /**
     * @return Expression
     */
    public function toExpression();

    /**
     * @return NormalizeOperand
     */
    public function toOperand();
}
