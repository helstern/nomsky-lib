<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

use ArrayObject;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

interface GroupedExpressionEliminator
{
    /**
     * Removes the first grouped expression from the list of expressions of a Concatenation expression
     *
     * @param \Helstern\Nomsky\Grammar\Expressions\Concatenation $expression
     * @param \ArrayObject $accumulator
     *
     * @return int the nr of resulting expressions
     *
     */
    public function removeFromConcatenation(Concatenation $expression, ArrayObject $accumulator);

    /**
     * Removes the first grouped expression from the list of expressions of a Choice expression
     *
     * @param \Helstern\Nomsky\Grammar\Expressions\Group $group
     * @param \ArrayObject $accumulator
     *
     * @return int the nr of resulting expressions
     */
    public function removeGroup(Group $group, ArrayObject $accumulator);
}
