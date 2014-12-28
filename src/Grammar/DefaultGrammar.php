<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Production\Production;

class DefaultGrammar implements Grammar
{
    /** @var string */
    protected $name;

    /** @var array|Production[] */
    protected $productions;

    /**
     * @param string $name
     * @param array|Production[] $productions
     */
    public function __construct($name, array $productions)
    {
        $this->name = $name;
        $this->productions = $productions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Production
     */
    public function getStartProduction()
    {
        return $this->productions[0];
    }

    /**
     * @return Production[]
     */
    public function getProductions()
    {
        return $this->productions;
    }

    /**
     * @return boolean
     */
    public function hasEpsilonProductions()
    {
        return false;
    }
}
