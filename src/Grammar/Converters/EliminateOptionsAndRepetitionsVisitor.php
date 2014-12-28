<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
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
        $children            = array_pop($this->stackOfChildren);
        $repeatedExpression  = array_pop($children);

        $nonTerminalSymbol = $this->createNewNonTerminal();
        $expression = SymbolAdapter::createAdapterForSymbol($nonTerminalSymbol);
        $this->setAsRootOrAddToStackOfChildren($expression);

        $this->addEpsilonAlternativeForList($nonTerminalSymbol, $repeatedExpression);
    }

    /**
     * this assumes a top down parsing method is going to be employed later on
     *
     * @param Symbol $nonTerminal
     * @param Expression $optionalExpression
     * @return Production
     */
    protected function addEpsilonAlternativeForList(Symbol $nonTerminal, Expression $optionalExpression)
    {
        $alternationItems = array(
            SymbolAdapter::createAdapterForEpsilon()
        );

        if ($optionalExpression instanceof ExpressionIterable) {
            /** @var $optionalExpression Expression */
            $items = array(
                new Group($optionalExpression),
                SymbolAdapter::createAdapterForSymbol($nonTerminal)
            );
            $alternationItems[] = new Sequence(array_shift($items), $items);
        } else {
            /** @var $optionalExpression Expression */
            $items = array(
                $optionalExpression,
                SymbolAdapter::createAdapterForSymbol($nonTerminal)
            );
            $alternationItems[] = new Sequence(array_shift($items), $items);
        }

        $alternation = new Alternation(array_shift($alternationItems), $alternationItems);

        $production = new Production($nonTerminal, $alternation);
        $this->epsilonAlternatives[] = $production;

        return $production;
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
        $children           = array_pop($this->stackOfChildren);
        $optionalExpression = array_pop($children);

        $newNonTerminal = $this->createNewNonTerminal();
        $expression = SymbolAdapter::createAdapterForSymbol($newNonTerminal);
        $this->setAsRootOrAddToStackOfChildren($expression);

        $this->addEpsilonAlternativeForItem($newNonTerminal, $optionalExpression);
    }

    protected function addEpsilonAlternativeForItem(Symbol $nonTerminal, Expression $optionalExpression)
    {
        $alternationItems = array(
            SymbolAdapter::createAdapterForEpsilon()
        );

        if ($optionalExpression instanceof Sequence) {
            $alternationItems[] = $optionalExpression;
        } elseif ($optionalExpression instanceof Alternation) {
            $alternationItems[] = new Group($optionalExpression);
        }

        $alternation = new Alternation(array_shift($alternationItems), $alternationItems);
        $production = new Production($nonTerminal, $alternation);
        $this->epsilonAlternatives[] = $production;

        return $production;
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
