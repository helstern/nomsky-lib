<?php namespace Helstern\Nomsky\Text;

interface TextMatch
{
    /**
     * @return string
     */
    public function getText();

    /**
     * @return int
     */
    public function getCharLength();

    /**
     * @return int
     */
    public function getByteLength();
}
