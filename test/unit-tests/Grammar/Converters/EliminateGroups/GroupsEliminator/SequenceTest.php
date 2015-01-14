<?php namespace Helstern\Nomsky\Grammar\Converters\GroupsEliminator;

use Helstern\Nomsky\Grammar\Converters\EliminateGroups\GroupsEliminator;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;

use Helstern\Nomsky\Grammar\TestUtils\ExpressionUtils;

class SequenceTest extends \PHPUnit_Framework_TestCase
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
     * @return ExpressionIterable|null
     */
    public function getDepthFirstWalkResult(Expression $e)
    {
        $visitor                    = new GroupsEliminator();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($e, $hierarchicVisitDispatcher);

        $walkResult = $visitor->getRoot();
        return $walkResult;
    }

    /**
     * (a  b  c  (1  2  3)) => (a  b  c  1  2  3)
     */
    public function testSequenceWithNestedSequenceAsLastChild()
    {
//      $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols      = array(
            $exprTestUtils->getGroupUtils()->createSequenceFromListOfStringSymbols(
                array('1', '2', '3')
            )
        );

        $sequence = $exprTestUtils->createSequenceFromSymbols(
            array_merge(
                $exprTestUtils->createListOfExpressions(array('a', 'b', 'c')),
                $listOfSymbols
            )
        );

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($sequence);
        $expectedExpressionWithoutGroups = $exprTestUtils->createSequenceFromListOfStringSymbols(
            array('a', 'b', 'c', '1', '2', '3')
        );

        $this->assertInstanceOf(
            get_class($expectedExpressionWithoutGroups),
            $actualExpressionWithoutGroups,
            'there should have been some expressions'
        );

        $actualListOfSymbols = $actualExpressionWithoutGroups->toArray();
        $expectedListOfSymbols = $expectedExpressionWithoutGroups->toArray();

        $assertFailMsgTpl = 'Expected the following alternation: %s';
        $this->assertEquals(
            $expectedListOfSymbols,
            $actualListOfSymbols,
            sprintf($assertFailMsgTpl, $exprTestUtils->serializeExpressionIterable($expectedExpressionWithoutGroups))
        );
    }

    /**
     * (a  b  c  (1 | 2  3)) => (a  b c 1 | a b c 2 3)
     */
    public function testSequenceWithNestedAlternationAsLastChild()
    {
//      $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols      = array(
            $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
                array(
                    $exprTestUtils->createTerminal('1'),
                    $exprTestUtils->createSequenceFromListOfStringSymbols(array('2', '3'))
                )
            )
        );

        $sequence = $exprTestUtils->createSequenceFromSymbols(
            array_merge(
                $exprTestUtils->createListOfExpressions(array('a', 'b', 'c')),
                $listOfSymbols
            )
        );

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($sequence);
        $expectedExpressionWithoutGroups = $exprTestUtils->createAlternationFromSymbols(
            array(
                $exprTestUtils->createSequenceFromListOfStringSymbols(array('a', 'b', 'c', '1')),
                $exprTestUtils->createSequenceFromListOfStringSymbols(array('a', 'b', 'c', '2', '3')),
            )
        );

        $this->assertInstanceOf(
            get_class($expectedExpressionWithoutGroups),
            $actualExpressionWithoutGroups,
            'there should have been some expressions'
        );

        $actualListOfSymbols = $actualExpressionWithoutGroups->toArray();
        $expectedListOfSymbols = $expectedExpressionWithoutGroups->toArray();

        $assertFailMsgTpl = 'Expected the following alternation: %s, received %s';
        $this->assertEquals(
            $expectedListOfSymbols,
            $actualListOfSymbols,
            sprintf(
                $assertFailMsgTpl,
                $exprTestUtils->serializeExpressionIterable($expectedExpressionWithoutGroups),
                $exprTestUtils->serializeExpressionIterable($actualExpressionWithoutGroups)
            )
        );
    }
}
