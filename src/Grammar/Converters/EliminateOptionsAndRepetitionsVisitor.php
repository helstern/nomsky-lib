<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\SymbolAdapter;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Rule\Production;
use Helstern\Nomsky\Grammar\Rule\Rule;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class EliminateOptionsAndRepetitionsVisitor implements HierarchyVisitor
{
    /** @var int */
    protected $nrOfNewNonTerminals = 0;

    /** @var array Expression[] */
    protected $epsilonAlternatives = array();

    /** @var Expression[] */
    protected $stackOfChildren;

    /** @var Expression */
    protected $root;

    protected function setAsRootOrAddToStackOfChildren(Expression $e)
    {
        if (empty($this->stackOfChildren)) {
            $this->root = $e;
        } else {
            $this->addToStackOfChildren($e);
        }
    }

    protected function addToStackOfChildren(Expression $e)
    {
        /** @var Expression[]|array $parentChildren */
        $parentChildren = array_pop($this->stackOfChildren);
        array_push($parentChildren, $e);
        array_push($this->stackOfChildren, $parentChildren);
    }

    /**
     * @return GenericSymbol
     */
    protected function createNewNonTerminal()
    {
        $this->nrOfNewNonTerminals++;
        $nonTerminalName = 'generatedNonTerminal' . $this->nrOfNewNonTerminals;

        $newNonTerminal = new GenericSymbol(Symbol::TYPE_NON_TERMINAL, $nonTerminalName);
        return $newNonTerminal;
    }

    protected function addEpsilonAlternative(Symbol $nonTerminal, Expression $expression)
    {
        /** @var Alternation $alternation */
        $alternation = null;
        $epsilonSymbol = SymbolAdapter::createAdapterForEpsilon();

        if ($expression instanceof Alternation) {
            $alternatives = $expression->toArray();
            array_push($alternatives, $epsilonSymbol);
            $alternation = new Alternation(array_shift($alternatives), $alternatives);
        } else {
            $alternation = new Alternation($expression, array($epsilonSymbol));
        }

        $production = new Production($nonTerminal, $alternation);
        $this->epsilonAlternatives[] = $production;

        return $production;
    }

    /**
     * @return Expression
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return Rule[]
     */
    public function getEpsilonAlternatives()
    {
        return $this->epsilonAlternatives;
    }

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function startVisitAlternation(Alternation $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function endVisitAlternation(Alternation $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);

        $firstChild = array_shift($children);
        $alternation = new Alternation($firstChild, $children);

        $this->setAsRootOrAddToStackOfChildren($alternation);

        return true;
    }

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function startVisitSequence(Sequence $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function endVisitSequence(Sequence $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);

        $firstChild = array_shift($children);
        $sequence = new Sequence($firstChild, $children);

        $this->setAsRootOrAddToStackOfChildren($sequence);

        return true;
    }

    /**
     * @param Group $expression
     * @return boolean
     */
    public function startVisitGroup(Group $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Group $expression
     * @return boolean
     */
    public function endVisitGroup(Group $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);
        $expression = array_pop($children);

        $group = new Group($expression);
        $this->setAsRootOrAddToStackOfChildren($group);

        return true;
    }

    /**
     * @param Repetition $expression
     * @return boolean
     */
    public function startVisitRepetition(Repetition $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Repetition $expression
     * @return boolean
     */
    public function endVisitRepetition(Repetition $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);
        $expression = array_pop($children);

        $nonTerminalSymbol = $this->createNewNonTerminal();

        $this->addEpsilonAlternative($nonTerminalSymbol, $expression);

        $expression = SymbolAdapter::createAdapterForSymbol($nonTerminalSymbol);
        $this->setAsRootOrAddToStackOfChildren($expression);
    }

    /**
     * @param Option $expression
     * @return boolean
     */
    public function startVisitOption(Option $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Option $expression
     * @return boolean
     */
    public function endVisitOption(Option $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);
        $expression = array_pop($children);

        $newNonTerminal = $this->createNewNonTerminal();

        $this->addEpsilonAlternative($newNonTerminal, $expression);

        $expression = SymbolAdapter::createAdapterForSymbol($newNonTerminal);
        $this->setAsRootOrAddToStackOfChildren($expression);
    }

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression)
    {
        /** @var Expression[]|array $parentChildren */
        $parentChildren = array_pop($this->stackOfChildren);
        array_push($parentChildren, $expression);
        array_push($this->stackOfChildren, $parentChildren);
    }
}
