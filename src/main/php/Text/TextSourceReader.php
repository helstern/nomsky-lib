<?php namespace Helstern\Nomsky\Text;

interface TextSourceReader extends TextReader
{
    /**
     * @return TextSource
     */
    public function getSource();
}
