<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

class ExpressionStackPushStrategy
{
    /** @var boolean */
    protected $parentIsGroup;

    /**
     * @param bool $parentIsGroup
     */
    public function __construct($parentIsGroup)
    {
        $this->parentIsGroup = $parentIsGroup;
    }

    /**
     * @param Expression $e
     * @return Group
     */
    protected function createGroup(Expression $e)
    {
        return new Group($e);
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Expressions\Expression $e
     * @param array $stack
     * @return array|null
     */
    public function pushChild(Expression $e, array $stack)
    {
        if ($this->parentIsGroup) {
            $group = $this->createGroup($e);

            /** @var Expression[]|array $parentChildren */
            $parentChildren = array_pop($stack);
            array_push($parentChildren, $group);
            array_push($stack, $parentChildren);

            return $stack;
        } else if(empty($stack)) {
            return null;
        } else {
            /** @var Expression[]|array $parentChildren */
            $parentChildren = array_pop($stack);
            array_push($parentChildren, $e);
            array_push($stack, $parentChildren);

            return $stack;
        }
    }

    /**
     * @param Combinator $c
     * @param array $stack
     * @return array
     */
    public function pushCombinator(Combinator $c, array $stack)
    {
        if ($this->parentIsGroup) {
            /** @var Expression[]|array $parentChildren */
            $parentChildren = array_pop($stack);
            array_push($parentChildren, $c);
            array_push($stack, $parentChildren);

            return $stack;
        }
    }
}
