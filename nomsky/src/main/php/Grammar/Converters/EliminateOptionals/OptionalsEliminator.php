<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateOptionals;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class OptionalsEliminator implements HierarchyVisitor
{
    /** @var NonTerminalNamingStrategy */
    protected $nonTerminalNamingStrategy;

    /** @var int */
    protected $nrOfNewNonTerminals = 0;

    /** @var array Expression[] */
    protected $epsilonAlternatives = array();

    /** @var Expression[] */
    protected $stackOfChildren;

    /** @var Expression */
    protected $root;

    /**
     * @param NonTerminalNamingStrategy $nonTerminalNamingStrategy
     */
    public function __construct(NonTerminalNamingStrategy $nonTerminalNamingStrategy)
    {
        $this->nonTerminalNamingStrategy = $nonTerminalNamingStrategy;
    }

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
        $nonTerminalName = $this->nonTerminalNamingStrategy->getName();

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
     * @return Production[]
     */
    public function getEpsilonAlternatives()
    {
        return $this->epsilonAlternatives;
    }

    /**
     * @param Alternative $expression
     * @return boolean
     */
    public function startVisitAlternation(Alternative $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Alternative $expression
     * @return boolean
     */
    public function endVisitAlternation(Alternative $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);

        $firstChild = array_shift($children);
        $alternation = new Alternative($firstChild, $children);

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
     * @param OptionalList $expression
     * @return boolean
     */
    public function startVisitOptionalList(OptionalList $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param OptionalList $expression
     * @return boolean
     */
    public function endVisitOptionalList(OptionalList $expression)
    {
        /** @var Expression[]|array $children */
        $children            = array_pop($this->stackOfChildren);
        $repeatedExpression  = array_pop($children);

        $nonTerminalSymbol = $this->createNewNonTerminal();
        $expression = ExpressionSymbol::createAdapterForSymbol($nonTerminalSymbol);
        $this->setAsRootOrAddToStackOfChildren($expression);

        $this->addEpsilonAlternativeForList($nonTerminalSymbol, $repeatedExpression);
    }

    /**
     * this assumes a top down parsing method is going to be employed later on
     *
     * @param Symbol $nonTerminal
     * @param Expression $optionalExpression
     * @return DefaultProduction
     */
    protected function addEpsilonAlternativeForList(Symbol $nonTerminal, Expression $optionalExpression)
    {
        $alternationItems = array(
            ExpressionSymbol::createAdapterForEpsilon()
        );

        if ($optionalExpression instanceof ExpressionIterable) {
            /** @var $optionalExpression Expression */
            $items = array(
                new Group($optionalExpression),
                ExpressionSymbol::createAdapterForSymbol($nonTerminal)
            );
            $alternationItems[] = new Sequence(array_shift($items), $items);
        } else {
            /** @var $optionalExpression Expression */
            $items = array(
                $optionalExpression,
                ExpressionSymbol::createAdapterForSymbol($nonTerminal)
            );
            $alternationItems[] = new Sequence(array_shift($items), $items);
        }

        $alternation = new Alternative(array_shift($alternationItems), $alternationItems);

        $production = new DefaultProduction($nonTerminal, $alternation);
        $this->epsilonAlternatives[] = $production;

        return $production;
    }


    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function startVisitOptionalItem(OptionalItem $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function endVisitOptionalItem(OptionalItem $expression)
    {
        /** @var Expression[]|array $children */
        $children           = array_pop($this->stackOfChildren);
        $optionalExpression = array_pop($children);

        $newNonTerminal = $this->createNewNonTerminal();
        $expression = ExpressionSymbol::createAdapterForSymbol($newNonTerminal);
        $this->setAsRootOrAddToStackOfChildren($expression);

        $this->addEpsilonAlternativeForItem($newNonTerminal, $optionalExpression);
    }

    protected function addEpsilonAlternativeForItem(Symbol $nonTerminal, Expression $optionalExpression)
    {
        $alternationItems = array(
            ExpressionSymbol::createAdapterForEpsilon()
        );

        if ($optionalExpression instanceof Sequence) {
            $alternationItems[] = $optionalExpression;
        } elseif ($optionalExpression instanceof Alternative) {
            $alternationItems[] = new Group($optionalExpression);
        } else {
            $alternationItems[] = $optionalExpression;
        }

        $alternation = new Alternative(array_shift($alternationItems), $alternationItems);
        $production = new DefaultProduction($nonTerminal, $alternation);
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
