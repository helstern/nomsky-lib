<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Production\Production;

interface Grammar
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return Symbol
     */
    public function getStartSymbol();

    /**
     * @return Production[]|array
     */
    public function getProductions();

    /**
     * @return Symbol[]
     */
    public function getTerminals();

    /**
     * @return Symbol[]
     */
    public function getNonTerminals();
}
