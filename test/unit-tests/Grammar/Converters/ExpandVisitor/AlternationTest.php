<?php namespace Helstern\Nomsky\Grammar\Converters\ExpandVisitor;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\SymbolAdapter;

use Helstern\Nomsky\Grammar\Converters\ExpressionTestUtils;

class AlternationTest extends \PHPUnit_Framework_TestCase
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
        $visitor                    = new Converters\EliminateGroupsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($e, $hierarchicVisitDispatcher);

        $walkResult = $visitor->getRoot();
        return $walkResult;
    }

    /**
     * (a | b | c | (1 | 2 | 3)) => (a | b | c | 1 | 2 | 3)
     */
    public function testAlternationWithNestedAlternationAsLastChild()
    {
//        $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols = $exprTestUtils->createListOfExpressions(array('3', '2', '1'));

        $alternation        = new Alternation(array_pop($listOfSymbols), array_reverse($listOfSymbols));
        $group              = new Group($alternation);
        $listOfSymbols      = array();
        $listOfSymbols[]    = $group;

        $listOfSymbols  = array_merge($listOfSymbols, $exprTestUtils->createListOfExpressions(array('c', 'b', 'a')));
        $alternation    = new Alternation(array_pop($listOfSymbols), array_reverse($listOfSymbols));

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($alternation);
        $expectedExpressionWithoutGroups = $exprTestUtils->createAlternationFromListOfStringSymbols(
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
     * ((a | b | c) | 1 | 2 | 3) => (a | b | c | 1 | 2 | 3)
     */
    public function testAlternationWithNestedAlternationAsFirstChild()
    {
//        $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols = $exprTestUtils->createListOfExpressions(array('a', 'b', 'c'));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);
        $group       = new Group($alternation);

        $listOfSymbols = array($group);
        $listOfSymbols = array_merge($listOfSymbols, $exprTestUtils->createListOfExpressions(array('1', '2', '3')));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($alternation);
        $expectedExpressionWithoutGroups = $exprTestUtils->createAlternationFromListOfStringSymbols(
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
     * (a | (b | c | 1) | 2 | 3) => (a | b | c | 1 | 2 | 3)
     */
    public function testAlternationWithNestedAlternationAsSibling()
    {
//        $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols = $exprTestUtils->createListOfExpressions(array('b', 'c', '1'));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);
        $group       = new Group($alternation);

        $listOfSymbols = array(SymbolAdapter::createAdapterForTerminal('a'), $group);
        $listOfSymbols = array_merge($listOfSymbols, $exprTestUtils->createListOfExpressions(array('2', '3')));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($alternation);
        $expectedExpressionWithoutGroups = $exprTestUtils->createAlternationFromListOfStringSymbols(
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
     * (a | (b | c | (x | y | z) | 1) | 2 | 3) => (a | b | c | x | y | z | 1 | 2 | 3)
     */
    public function testAlternationWithMultipleNestedAlternationAsSibling()
    {
//        $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols = $exprTestUtils->createListOfExpressions(array('x', 'y', 'z'));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);
        $group       = new Group($alternation);

        $listOfSymbols = array_merge(
            $exprTestUtils->createListOfExpressions(array('b', 'c')),
            array($group, SymbolAdapter::createAdapterForTerminal('1'))
        );
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);
        $group       = new Group($alternation);

        $listOfSymbols = array_merge(
            array(SymbolAdapter::createAdapterForTerminal('a'), $group),
            $exprTestUtils->createListOfExpressions(array('2', '3'))
        );
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($alternation);
        $expectedExpressionWithoutGroups = $exprTestUtils->createAlternationFromListOfStringSymbols(
            array('a', 'b', 'c', 'x', 'y', 'z', '1', '2', '3')
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
     * (a | b | c | (1 2 | 3)) => (a | b | c | 1 2 | 3)
     */
    public function testAlternationWithNestedSequenceAsLastChild()
    {
//        $this->markTestSkipped('s');
        $exprTestUtils = $this->getExpressionTestUtils();

        $listOfSymbols = $exprTestUtils->createListOfExpressions(array('a', 'b', 'c'));
        //add group
        $listOfSymbols[] = $exprTestUtils->getGroupUtils()->createAlternationFromSymbols(
            array(
                $exprTestUtils->createSequenceFromListOfStringSymbols(array('1', '2')),
                $exprTestUtils->createTerminal('3')
            )
        );
        $alternation = $exprTestUtils->createAlternationFromSymbols($listOfSymbols);

        $actualExpressionWithoutGroups = $this->getDepthFirstWalkResult($alternation);
        $expectedExpressionWithoutGroups = $exprTestUtils->createAlternationFromListOfStringSymbols(
            array(
                'a', 'b', 'c',
                $exprTestUtils->createSequenceFromListOfStringSymbols(array('1', '2')),
                '3'
            )
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
}
