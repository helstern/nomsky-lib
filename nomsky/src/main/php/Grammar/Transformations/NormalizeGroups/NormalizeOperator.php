<?php namespace Helstern\Nomsky\Grammar\Transformations\NormalizeGroups;

use Helstern\Nomsky\Grammar\Transformations\NormalizeGroups\OperationResult\ResultInterface;
use Helstern\Nomsky\Grammar\Expressions\Expression;

interface NormalizeOperator
{
    /**
     * (a | c) * (x | y)
     *
     * @param array|Expression[] $leftGroupItems
     * @param array|Expression[] $rightGroupItems
     * @return ResultInterface
     */
    public function operateOnAlternationAndAlternation(array $leftGroupItems, array $rightGroupItems);

    /**
     * (a | c) * (x y)
     *
     * @param array|Expression[] $leftGroupItems
     * @param array|Expression[] $rightGroupItems
     * @return ResultInterface
     */
    public function operateOnAlternationAndSequence(array $leftGroupItems, array $rightGroupItems);

    /**
     * (a c) * (x | y)
     *
     * @param array|Expression[] $leftGroupItems
     * @param array|Expression[] $rightGroupItems
     * @return ResultInterface
     */
    public function operateOnSequenceAndAlternation(array $leftGroupItems, array $rightGroupItems);

    /**
     * (a c) * (x y)
     *
     * @param array|Expression[] $leftGroupItems
     * @param array|Expression[] $rightGroupItems
     * @return ResultInterface
     */
    public function operateOnSequenceAndSequence(array $leftGroupItems, array $rightGroupItems);
}
