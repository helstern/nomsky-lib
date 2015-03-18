<?php namespace Helstern\Nomsky\Dispatcher;

interface DoubleDispatcherBuilder
{
    /**
     * @param string $type
     * @return DoubleDispatcherBuilder
     */
    public function addDispatchArgumentType($type);

    /**
     * @return DoubleDispatcher
     */
    public function build();
}
