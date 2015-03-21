<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz;

use Helstern\Nomsky\Graphviz\DotWriter;

class Formatter
{
    protected $bodyIndentSize = 1;

    /** @var string */
    protected $indentWhitespaceSize = 2;

    /**
     * @param $size
     * @param DotWriter $dotWriter
     * @return $this
     */
    public function indent($size, DotWriter $dotWriter)
    {
        $dotWriter->writeWhitespace($this->indentWhitespaceSize * ($this->bodyIndentSize + $size));
        return $this;
    }

    public function whitespace($size, DotWriter $dotWriter)
    {
        $dotWriter->writeWhitespace($size);
        return $this;
    }
}
