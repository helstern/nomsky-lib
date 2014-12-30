<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\OperationResult;

use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsNormalizer\NormalizeOperand;
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
