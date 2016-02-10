<?php namespace Helstern\Nomsky\Text;

interface PcrePattern
{
    /**
     * @return PcreOptions
     */
    public function anchorAtStart();

    /**
     * @return PcreOptions
     */
    public function anchorAtEnd();

    /**
     * @return string
     */
    function compile();
}
