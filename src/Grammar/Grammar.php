<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Rule\Production;
use Helstern\Nomsky\Grammar\Rule\Rule;

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
     * @return Rule[]
     */
    public function getRules();

    /**
     * @return boolean
     */
    public function hasEpsilonRules();
}
