<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Expressions\Walker\Walks;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\Comparator\HashCodeComparator;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class StandardGrammar implements Grammar
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
     * @return Symbol
     */
    public function getStartSymbol()
    {
        $firstProduction = $this->productions[0];
        $startSymbol = $firstProduction->getFirstSymbol();

        return $startSymbol;
    }

    /**
     * @return Production[]
     */
    public function getProductions()
    {
        return $this->productions;
    }

    /**
     * @return Symbol[]
     */
    public function getTerminals()
    {
        $visitor        = new SymbolCollectorVisitor(SymbolTypeEquals::newInstanceMatchingTerminals());
        $walks          = Walks::singletonInstance();

        $productions    = $this->getProductions();
        foreach ($productions as $production) {
            $expression = $production->getExpression();
            $walks->depthFirstWalk($expression, $visitor);
        }

        $terminals = $visitor->getCollected();
        return $terminals;
    }

    /**
     * @return Symbol[]
     */
    public function getNonTerminals()
    {
        $productions    = $this->getProductions();
        $collected   = array();
        foreach ($productions as $production) {
            $collected[] = $production->getNonTerminal();
        }

        $nonTerminals = HashCodeComparator::singletonInstance()->unique($collected);
        return $nonTerminals;
    }
}
