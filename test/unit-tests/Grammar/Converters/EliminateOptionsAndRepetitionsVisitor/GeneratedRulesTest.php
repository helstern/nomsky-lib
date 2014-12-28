<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateOptionsAndRepetitionsVisitor;

use Helstern\Nomsky\Grammar\Converters\ExpressionTestUtils;
use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Converters;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Rule\Production;
use SebastianBergmann\Exporter\Exception;

class GeneratedRulesTest extends \PHPUnit_Framework_TestCase
{
    /** @var ExpressionTestUtils */
    protected $expressionTestUtils;

    /**
     * @return ExpressionTestUtils
     */
    public function getExpressionTestUtils()
    {
        if (is_null($this->expressionTestUtils)) {
            $this->expressionTestUtils = new ExpressionTestUtils();
        }

        return $this->expressionTestUtils;
    }

    /**
     * @param Expression $e
     * @param \Helstern\Nomsky\Grammar\Converters\EliminateOptionsAndRepetitionsVisitor $visitor
     * @return ExpressionIterable|null
     */
    public function getDepthFirstWalkResult(Expression $e, Converters\EliminateOptionsAndRepetitionsVisitor $visitor)
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
    public function testGeneratedForRepetition()
    {
//      $this->markTestSkipped('s');

        $exprTestUtils = $this->getExpressionTestUtils();

        $initialList      = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $initialList[]    = new Repetition($exprTestUtils->createTerminal('c'));
        $initialList[]    = $exprTestUtils->createTerminal('d');
        $initialExpression = new Sequence(array_shift($initialList), $initialList);

        $visitor = new Converters\EliminateOptionsAndRepetitionsVisitor();
        $this->getDepthFirstWalkResult($initialExpression, $visitor);

        $epsilonAlternatives = $visitor->getEpsilonAlternatives();
        $assertFailMsgTpl = 'Expected 1 rules to be generated for a repetion. Instead %s were generated';
        $this->assertEquals(1, count($epsilonAlternatives), sprintf($assertFailMsgTpl, count($epsilonAlternatives)));

        /** @var Production $production */
        $production = array_pop($epsilonAlternatives);
        /** @var Alternation $actualExpression */
        $actualExpression = $production->getExpression();

        /** @var \Exception $castAlternationException */
        $castAlternationException = null;
        try {
            $cast = function (Alternation $alternation) { return $alternation; };
            $cast($actualExpression);
        } catch (\Exception $castAlternationException) {

        }
        $this->assertNull($castAlternationException, 'Expected an alternation');

        $expectedItems = array(
            $exprTestUtils->createTerminal(''), //epsilon
            $exprTestUtils->createSequenceFromListOfStringSymbols(
                array(
                    $exprTestUtils->createTerminal('c'),
                    $exprTestUtils->createNonTerminal('generatedNonTerminal1')
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
}
