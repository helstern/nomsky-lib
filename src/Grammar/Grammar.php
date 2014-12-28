<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Production\ProductionInterface;

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
     * @return ProductionInterface[]
     */
    public function getRules();

    /**
     * @return boolean
     */
    public function hasEpsilonRules();
}
