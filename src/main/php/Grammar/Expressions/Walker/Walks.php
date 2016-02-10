<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Visitor\CompositeVisit\CompleteVisitDispatcher as CompositeVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Visitor\CompositeVisitor;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher as HierachicalVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;

class Walks
{
    /** @var Walks */
    private static $singletonInstance;

    /**
     * @return Walks
     */
    public static function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

    /**
     * @param Expression $expression
     * @param CompositeVisitor|HierarchyVisitor $visitor
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function depthFirstWalk(Expression $expression, $visitor)
    {
        if ($visitor instanceof CompositeVisitor) {
            $walkState = $this->depthFirstCompositeWalk($expression, $visitor);
            return $walkState;
        } elseif ($visitor instanceof HierarchyVisitor) {
            $walkState = $this->depthFirstHierarchicWalk($expression, $visitor);
            return $walkState;
        } else {
            throw new \InvalidArgumentException(
                'Expected second argument to be a CompositeVisitor or HierarchicVisitor'
            );
        }
    }

    /**
     * @param Expression $expression
     * @param CompositeVisitor $visitor
     * @return bool
     */
    public function depthFirstCompositeWalk(Expression $expression, CompositeVisitor $visitor)
    {
        $hierarchicVisitDispatcher  = new CompositeVisitDispatcher($visitor);
        $walker                     = new DepthFirstStackBasedWalker();
        $walkState = $walker->walk($expression, $hierarchicVisitDispatcher);

        return $walkState;
    }

    /**
     * @param Expression $expression
     * @param HierarchyVisitor $visitor
     * @return bool
     */
    public function depthFirstHierarchicWalk(Expression $expression, HierarchyVisitor $visitor)
    {
        $hierarchicVisitDispatcher  = new HierachicalVisitDispatcher($visitor);
        $walker                     = new DepthFirstStackBasedWalker();
        $walkState = $walker->walk($expression, $hierarchicVisitDispatcher);

        return $walkState;
    }

}
