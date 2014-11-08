<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\WalkState;

use Helstern\Nomsky\Grammar\Expressions\Expression;

class KeepWalkingStateMachine implements WalkStateMachine
{
    /** @var KeepWalkingStateMachine */
    static private $singletonInstance;

    /**
     * @return KeepWalkingStateMachine
     */
    static public function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }

    /**
     * @param Expression $root
     * @return boolean
     */
    public function startWalking(Expression $root)
    {
        return true;
    }

    /**
     * @param Expression $lastVisited
     * @return boolean
     */
    public function continueWalkingAfterVisit(Expression $lastVisited)
    {
        return true;
    }
}
