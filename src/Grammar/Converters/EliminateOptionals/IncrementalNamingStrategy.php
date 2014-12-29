<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateOptionals;

class IncrementalNamingStrategy implements NonTerminalNamingStrategy
{
    protected $generatedNames = 0;

    protected $namePrefix;

    protected $separator = '-';

    public function __construct($namePrefix = 'GeneratedNonTerminal')
    {
        $this->namePrefix = $namePrefix;
    }

    /** string */
    public function getName()
    {
        $this->generatedNames++;
        return $this->namePrefix . $this->separator . $this->generatedNames;
    }
}
