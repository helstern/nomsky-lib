<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

class NoDispatchDispatcher extends AbstractDispatcher
{
    /** @var NoDispatchDispatcher */
    static private $singletonInstance;

    /**
     * @return NoDispatchDispatcher
     */
    static public function singletonInstance()
    {
        if (is_null(self::$singletonInstance)) {
            self::$singletonInstance = new self;
        }

        return self::$singletonInstance;
    }
}
