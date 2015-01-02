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
     * @return Production
     */
    public function getStartProduction();

    /**
     * @return Production[]
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
