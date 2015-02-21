<?php namespace Helstern\Nomsky\Text;

interface TextSource
{
    /**
     * @return string
     */
    public function retrieveText();

    /**
     * @return TextSourceReader
     */
    public function createReader();
}
