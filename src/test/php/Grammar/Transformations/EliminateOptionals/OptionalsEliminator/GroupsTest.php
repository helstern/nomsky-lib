<?php namespace Helstern\Nomsky\Grammar\Converter\OptionalsEliminator;

use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Transformations\EliminateOptionals\NonTerminalNamingStrategy;
use Helstern\Nomsky\Grammar\Transformations\EliminateOptionals\OptionalsEliminator;
use Helstern\Nomsky\Grammar\TestUtils\ExpressionUtils;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

use Helstern\Nomsky\Grammar\Converter;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;

class GroupsTest extends \PHPUnit_Framework_TestCase
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
     * @param NonTerminalNamingStrategy $namingStrategy
     *
*@return Expression|null
     */
    public function getDepthFirstWalkResult(Expression $e, NonTerminalNamingStrategy $namingStrategy)
    {
        $visitor                    = new OptionalsEliminator($namingStrategy);
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
                new Optional($exprTestUtils->createTerminal('2')),
                $exprTestUtils->createTerminal('3')
            )
        );
        $initialExpression = new Concatenation(array_shift($initialList), $initialList);

        $namingStrategy = $exprTestUtils->createNonTerminalNamingStrategy();
        /** @var ExpressionIterable $actualExpression */
        $actualExpression = $this->getDepthFirstWalkResult($initialExpression, $namingStrategy);

        $this->assertInstanceOf(
            get_class($initialExpression),
            $actualExpression,
            'there should have been some expressions'
        );

        $expectedList   = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $namingStrategy = $exprTestUtils->createNonTerminalNamingStrategy();
        $expectedList[] = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createTerminal('1'),
                $exprTestUtils->createNonTerminal($namingStrategy->getName()),
                $exprTestUtils->createTerminal('3')
            )
        );
        $expectedExpression = new Concatenation(array_shift($expectedList), $expectedList);

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
                new Repetition($exprTestUtils->createTerminal('2')),
                $exprTestUtils->createTerminal('3')
            )
        );
        $initialExpression = new Concatenation(array_shift($initialList), $initialList);
        $namingStrategy = $exprTestUtils->createNonTerminalNamingStrategy();
        $actualExpression = $this->getDepthFirstWalkResult($initialExpression, $namingStrategy);

        $this->assertInstanceOf(
            get_class($initialExpression),
            $actualExpression,
            'there should have been some expressions'
        );

        $expectedList   = $exprTestUtils->createListOfExpressions(array('a', 'b'));
        $namingStrategy = $exprTestUtils->createNonTerminalNamingStrategy();
        $expectedList[] = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createTerminal('1'),
                $exprTestUtils->createNonTerminal($namingStrategy->getName()),
                $exprTestUtils->createTerminal('3')
            )
        );
        $expectedExpression = new Concatenation(array_shift($expectedList), $expectedList);

        $assertFailMsgTpl = 'Expected the following sequence: %s';
        $this->assertEquals(
            $expectedExpression->toArray(),
            $actualExpression->toArray(),
            sprintf($assertFailMsgTpl, $exprTestUtils->serializeExpressionIterable($expectedExpression))
        );
    }
}
