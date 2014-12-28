<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Production\Production;

interface Grammar
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return DefaultProduction
     */
    public function getStartProduction();

    /**
     * @return Production[]
     */
    public function getProductions();

    /**
     * @return boolean
     */
    public function hasEpsilonProductions();
}
