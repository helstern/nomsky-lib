<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateOptionals\OptionalsEliminator;

use Helstern\Nomsky\Grammar\Converters\EliminateOptionals\OptionalsEliminator;

use Helstern\Nomsky\Grammar\TestUtils\ExpressionUtils;
use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Converters;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;

class GeneratedRulesTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExpressionUtils */
    protected $expressionTestUtils;

    /**
     * @return ExpressionUtils
     */
    public function getExpressionTestUtils()
    {
        if (is_null($this->expressionTestUtils)) {
            $this->expressionTestUtils = new ExpressionUtils();
        }

        return $this->expressionTestUtils;
    }

    /**
     * @param Expression $e
     * @param \Helstern\Nomsky\Grammar\Converters\EliminateOptionals\OptionalsEliminator $visitor
     * @return ExpressionIterable|null
     */
    public function walkAndVisitExpression(Expression $e, OptionalsEliminator $visitor)
    {
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($e, $hierarchicVisitDispatcher);

        $walkResult = $visitor->getRoot();
        return $walkResult;
    }

    /**
     * a b { c } d =>
     *  a b generatedNonTerminal1 d
     *  epsilon | c generatedNonTerminal1
     */
    public function testGeneratedRulesForOptionalList()
    {
//      $this->markTestSkipped('s');

        $exprTestUtils = $this->getExpressionTestUtils();

        $initialList      = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $initialList[]    = new OptionalList($exprTestUtils->createTerminal('c'));
        $initialList[]    = $exprTestUtils->createTerminal('d');
        $initialExpression = new Sequence(array_shift($initialList), $initialList);

        $visitor = new OptionalsEliminator($exprTestUtils->createNonTerminalNamingStrategy());
        $this->walkAndVisitExpression($initialExpression, $visitor);

        $epsilonAlternatives = $visitor->getEpsilonAlternatives();
        $assertFailMsgTpl = 'Expected 1 rules to be generated for a repetion. Instead %s were generated';
        $this->assertEquals(1, count($epsilonAlternatives), sprintf($assertFailMsgTpl, count($epsilonAlternatives)));

        /** @var DefaultProduction $production */
        $production = array_pop($epsilonAlternatives);
        /** @var Alternative $actualExpression */
        $actualExpression = $production->getExpression();

        /** @var \Exception $castAlternationException */
        $castAlternationException = null;
        try {
            $castToAlternation = function (Alternative $alternation) { return $alternation; };
            $castToAlternation($actualExpression);
        } catch (\Exception $castAlternationException) {
            /** on purpose left */
        }
        $this->assertNull($castAlternationException, 'Expected an alternation');

        $namingStrategy = $exprTestUtils->createNonTerminalNamingStrategy();
        $expectedItems = array(
            $exprTestUtils->createTerminal(''), //epsilon
            $exprTestUtils->createSequenceFromListOfStringSymbols(
                array(
                    $exprTestUtils->createTerminal('c'),
                    $exprTestUtils->createNonTerminal($namingStrategy->getName())
                )
            )
        );
        $actualItems = $actualExpression->toArray();

        $assertFailMsgTpl = 'Expected the following sequence: %s';
        $assertFailMsg = sprintf(
            $assertFailMsgTpl,
            $exprTestUtils->serializeExpressionIterable($exprTestUtils->createAlternationFromSymbols($actualItems))
        );
        $this->assertEquals($expectedItems, $actualItems, $assertFailMsg);
    }

    /**
     * a b [ c ] d =>
     *  a b generatedNonTerminal1 d
     *  epsilon | c
     */
    public function testGeneratedRulesForOptionalItems()
    {
//      $this->markTestSkipped('s');

        $exprTestUtils = $this->getExpressionTestUtils();

        $initialList      = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $initialList[]    = new OptionalItem($exprTestUtils->createTerminal('c'));
        $initialList[]    = $exprTestUtils->createTerminal('d');
        $initialExpression = new Sequence(array_shift($initialList), $initialList);

        $visitor = new OptionalsEliminator($exprTestUtils->createNonTerminalNamingStrategy());
        $this->walkAndVisitExpression($initialExpression, $visitor);

        $epsilonAlternatives = $visitor->getEpsilonAlternatives();
        $assertFailMsgTpl = 'Expected 1 rules to be generated for a repetion. Instead %s were generated';
        $this->assertEquals(1, count($epsilonAlternatives), sprintf($assertFailMsgTpl, count($epsilonAlternatives)));

        /** @var DefaultProduction $production */
        $production = array_pop($epsilonAlternatives);
        /** @var Alternative $actualExpression */
        $actualExpression = $production->getExpression();

        /** @var \Exception $castAlternationException */
        $castAlternationException = null;
        try {
            $castToAlternation = function (Alternative $alternation) { return $alternation; };
            $castToAlternation($actualExpression);
        } catch (\Exception $castAlternationException) {
            /** on purpose left */
        }
        $this->assertNull($castAlternationException, 'Expected an alternation');

        $expectedItems = array(
            $exprTestUtils->createTerminal(''), //epsilon
            $exprTestUtils->createTerminal('c')
        );
        $actualItems = $actualExpression->toArray();

        $assertFailMsgTpl = 'Expected the following sequence: %s';
        $assertFailMsg = sprintf(
            $assertFailMsgTpl,
            $exprTestUtils->serializeExpressionIterable($exprTestUtils->createAlternationFromSymbols($actualItems))
        );
        $this->assertEquals($expectedItems, $actualItems, $assertFailMsg);
    }
}
