<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateOptionals;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\StandardSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class OptionalsEliminator implements HierarchyVisitor
{
    /** @var NonTerminalNamingStrategy */
    private $nonTerminalNamingStrategy;

    /** @var int */
    private $nrOfNewNonTerminals = 0;

    /** @var array Expression[] */
    private $epsilonAlternatives = array();

    /** @var Expression[] */
    private $stackOfChildren;

    /** @var Expression */
    private $root;

    /**
     * @param NonTerminalNamingStrategy $nonTerminalNamingStrategy
     */
    public function __construct(NonTerminalNamingStrategy $nonTerminalNamingStrategy)
    {
        $this->nonTerminalNamingStrategy = $nonTerminalNamingStrategy;
    }

    private function setAsRootOrAddSibling(Expression $e)
    {
        if (empty($this->stackOfChildren)) {
            $this->root = $e;
        } else {
            $this->addSibling($e);
        }
    }

    /**
     * @param Expression $e
     */
    private function addSibling(Expression $e)
    {
        /** @var Expression[]|array $siblings */
        $siblings = array_pop($this->stackOfChildren);
        array_push($siblings, $e);
        array_push($this->stackOfChildren, $siblings);
    }

    /**
     * @return StandardSymbol
     */
    protected function createNewNonTerminal()
    {
        $this->nrOfNewNonTerminals++;
        $nonTerminalName = $this->nonTerminalNamingStrategy->getName();

        $newNonTerminal = new StandardSymbol(Symbol::TYPE_NON_TERMINAL, $nonTerminalName);
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
     * @param Choice $expression
     *
     * @return boolean
     */
    public function startVisitChoice(Choice $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Choice $expression
     *
     * @return boolean
     */
    public function endVisitChoice(Choice $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);

        $firstChild = array_shift($children);
        $alternation = new Choice($firstChild, $children);

        $this->setAsRootOrAddSibling($alternation);

        return true;
    }

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function startVisitConcatenation(Concatenation $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function endVisitConcatenation(Concatenation $expression)
    {
        /** @var Expression[]|array $children */
        $children = array_pop($this->stackOfChildren);

        $firstChild = array_shift($children);
        $sequence = new Concatenation($firstChild, $children);

        $this->setAsRootOrAddSibling($sequence);

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
        $this->setAsRootOrAddSibling($group);

        return true;
    }

    /**
     * @param Repetition $expression
     *
     * @return boolean
     */
    public function startVisitRepetition(Repetition $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Repetition $expression
     *
     * @return boolean
     */
    public function endVisitRepetition(Repetition $expression)
    {
        /** @var Expression[]|array $children */
        $children            = array_pop($this->stackOfChildren);
        $repeatedExpression  = array_pop($children);

        $nonTerminalSymbol = $this->createNewNonTerminal();
        $expression = ExpressionSymbol::createAdapterForSymbol($nonTerminalSymbol);
        $this->setAsRootOrAddSibling($expression);

        $this->addEpsilonAlternativeForList($nonTerminalSymbol, $repeatedExpression);
    }

    /**
     * this assumes a top down parsing method is going to be employed later on
     *
     * @param Symbol $nonTerminal
     * @param Expression $optionalExpression
     *
     * @return StandardProduction
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
            $alternationItems[] = new Concatenation(array_shift($items), $items);
        } else {
            /** @var $optionalExpression Expression */
            $items = array(
                $optionalExpression,
                ExpressionSymbol::createAdapterForSymbol($nonTerminal)
            );
            $alternationItems[] = new Concatenation(array_shift($items), $items);
        }

        $alternation = new Choice(array_shift($alternationItems), $alternationItems);

        $production = new StandardProduction($nonTerminal, $alternation);
        $this->epsilonAlternatives[] = $production;

        return $production;
    }


    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function startVisitOptional(Optional $expression)
    {
        $this->stackOfChildren[] = array();

        return true;
    }

    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function endVisitOptional(Optional $expression)
    {
        /** @var Expression[]|array $children */
        $children           = array_pop($this->stackOfChildren);
        $optionalExpression = array_pop($children);

        $newNonTerminal = $this->createNewNonTerminal();
        $expression = ExpressionSymbol::createAdapterForSymbol($newNonTerminal);
        $this->setAsRootOrAddSibling($expression);

        $this->addEpsilonAlternativeForItem($newNonTerminal, $optionalExpression);
    }

    protected function addEpsilonAlternativeForItem(Symbol $nonTerminal, Expression $optionalExpression)
    {
        $alternationItems = array(
            ExpressionSymbol::createAdapterForEpsilon()
        );

        if ($optionalExpression instanceof Concatenation) {
            $alternationItems[] = $optionalExpression;
        } elseif ($optionalExpression instanceof Choice) {
            $alternationItems[] = new Group($optionalExpression);
        } else {
            $alternationItems[] = $optionalExpression;
        }

        $alternation = new Choice(array_shift($alternationItems), $alternationItems);
        $production = new StandardProduction($nonTerminal, $alternation);
        $this->epsilonAlternatives[] = $production;

        return $production;
    }


    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression)
    {
        if (empty($this->root) && empty($this->stackOfChildren)) {
            $this->root = $expression;
        } elseif (!empty($this->stackOfChildren)) {
            $this->addSibling($expression);
        }
    }
}
