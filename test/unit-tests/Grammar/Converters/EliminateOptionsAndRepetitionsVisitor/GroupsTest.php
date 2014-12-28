<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateOptionsAndRepetitionsVisitor;

use Helstern\Nomsky\Grammar\Converters\ExpressionTestUtils;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Converters;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;

class GroupsTest extends \PHPUnit_Framework_TestCase
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
     * @return ExpressionIterable|null
     */
    public function getDepthFirstWalkResult(Expression $e)
    {
        $visitor                    = new Converters\EliminateOptionsAndRepetitionsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($e, $hierarchicVisitDispatcher);

        $walkResult = $visitor->getRoot();
        return $walkResult;
    }

    /**
     * a b (1 | [ 2 ] | 3) => a b (1 | generatedNonTerminal1 | 3)
     */
    public function testGroupWithOptionalSymbol()
    {
//      $this->markTestSkipped('s');

        $exprTestUtils = $this->getExpressionTestUtils();

        $initialList      = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $initialList[]    = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createTerminal('1'),
                new OptionalItem($exprTestUtils->createTerminal('2')),
                $exprTestUtils->createTerminal('3')
            )
        );
        $initialExpression = new Sequence(array_shift($initialList), $initialList);
        $actualExpression = $this->getDepthFirstWalkResult($initialExpression);

        $this->assertInstanceOf(
            get_class($initialExpression),
            $actualExpression,
            'there should have been some expressions'
        );

        $expectedList   = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $expectedList[] = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createTerminal('1'),
                $exprTestUtils->createNonTerminal('generatedNonTerminal1'),
                $exprTestUtils->createTerminal('3')
            )
        );
        $expectedExpression = new Sequence(array_shift($expectedList), $expectedList);

        $assertFailMsgTpl = 'Expected the following sequence: %s';
        $this->assertEquals(
            $expectedExpression->toArray(),
            $actualExpression->toArray(),
            sprintf($assertFailMsgTpl, $exprTestUtils->serializeExpressionIterable($expectedExpression))
        );
    }

    /**
     * a b (1 | { 2 } | 3) => a b (1 | generatedNonTerminal1 | 3)
     */
    public function testGroupWithRepeatedSymbol()
    {
//      $this->markTestSkipped('s');

        $exprTestUtils = $this->getExpressionTestUtils();

        $initialList      = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $initialList[]    = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createTerminal('1'),
                new OptionalList($exprTestUtils->createTerminal('2')),
                $exprTestUtils->createTerminal('3')
            )
        );
        $initialExpression = new Sequence(array_shift($initialList), $initialList);
        $actualExpression = $this->getDepthFirstWalkResult($initialExpression);

        $this->assertInstanceOf(
            get_class($initialExpression),
            $actualExpression,
            'there should have been some expressions'
        );

        $expectedList   = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $expectedList[] = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createTerminal('1'),
                $exprTestUtils->createNonTerminal('generatedNonTerminal1'),
                $exprTestUtils->createTerminal('3')
            )
        );
        $expectedExpression = new Sequence(array_shift($expectedList), $expectedList);

        $assertFailMsgTpl = 'Expected the following sequence: %s';
        $this->assertEquals(
            $expectedExpression->toArray(),
            $actualExpression->toArray(),
            sprintf($assertFailMsgTpl, $exprTestUtils->serializeExpressionIterable($expectedExpression))
        );
    }
}
