<?php namespace Helstern\Nomsky\Graphviz;

interface DotFile
{
    /**
     * @return string
     */
    public function getLineTerminator();

    /**
     * @return int
     */
    public function addLineTerminator();

    /**
     * @param string $line
     * @return int
     */
    public function addLineAndTerminator($line);

    /**
     * @param string $text
     * @return int
     */
    public function add($text);
}
