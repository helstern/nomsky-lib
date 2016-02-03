<?php namespace Helstern\Nomsky\Text;

interface TextSource
{
    /**
     * @return string
     */
    public function retrieveText();

    /**
     * @return DeprecatedTextReader
     */
    public function createReader();
}
