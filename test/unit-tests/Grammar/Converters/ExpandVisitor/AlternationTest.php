<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroupsVisitor;

use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\SymbolAdapter;

class AlternationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $listOfStringSymbols
     * @return Alternation
     */
    public function createAlternationFromListOfStringSymbols(array $listOfStringSymbols)
    {
        $listOfSymbols = $this->createListOfSymbols($listOfStringSymbols);
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        return $alternation;
    }

    /**
     * @param array $listOfStringSymbols
     * @return array|SymbolAdapter[]
     */
    public function createListOfSymbols(array $listOfStringSymbols)
    {
        $listOfSymbolObjects = array();
        foreach($listOfStringSymbols as $stringSymbol) {
            $listOfSymbolObjects[] = SymbolAdapter::createTerminal($stringSymbol);
        }

        return $listOfSymbolObjects;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Expressions\ExpressionIterable $expression
     * @internal param array|\Helstern\Nomsky\Grammar\Expressions\SymbolAdapter[] $listOfSymbols
     * @return string|null
     */
    public function serializeExpressionIterable(ExpressionIterable $expression)
    {
        $listOfSerializedObjects = array();
        /** @var $symbolObject SymbolAdapter */
        foreach($expression as $symbolObject) {
            if ($symbolObject instanceof SymbolAdapter) {
                $listOfSerializedObjects[] = $symbolObject->hashCode();
            } else {
                return null;
            }
        }

        if ($expression instanceof Alternation) {
            $separator = '| ';
        } elseif ($expression instanceof Sequence) {
            $separator = ' ';
        } else {
            $separator = ', ';
        }

        return implode($separator, $listOfSerializedObjects);
    }

    /**
     * (a | b | c | (1 | 2 | 3)) => (a | b | c | 1 | 2 | 3)
     */
    public function testAlternationWithNestedAlternationAsLastChild()
    {
//        $this->markTestSkipped('s');

        $listOfSymbols = $this->createListOfSymbols(array('3', '2', '1'));

        $alternation        = new Alternation(array_pop($listOfSymbols), array_reverse($listOfSymbols));
        $group              = new Group($alternation);
        $listOfSymbols      = array();
        $listOfSymbols[]    = $group;

        $listOfSymbols  = array_merge($listOfSymbols, $this->createListOfSymbols(array('c', 'b', 'a')));
        $alternation    = new Alternation(array_pop($listOfSymbols), array_reverse($listOfSymbols));

        $visitor                    = new Converters\EliminateGroupsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($alternation, $hierarchicVisitDispatcher);

        /** @var Alternation $actualExpressionWithoutGroups */
        $actualExpressionWithoutGroups = $visitor->getRoot();
        $expectedExpressionWithoutGroups = $this->createAlternationFromListOfStringSymbols(
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
            sprintf($assertFailMsgTpl, $this->serializeExpressionIterable($expectedExpressionWithoutGroups))
        );
    }

    /**
     * ((a | b | c) | 1 | 2 | 3) => (a | b | c | 1 | 2 | 3)
     */
    public function testAlternationWithNestedAlternationAsFirstChild()
    {
//        $this->markTestSkipped('s');

        $listOfSymbols = $this->createListOfSymbols(array('a', 'b', 'c'));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);
        $group       = new Group($alternation);

        $listOfSymbols = array($group);
        $listOfSymbols = array_merge($listOfSymbols, $this->createListOfSymbols(array('1', '2', '3')));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        $visitor                    = new Converters\EliminateGroupsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($alternation, $hierarchicVisitDispatcher);

        /** @var Alternation $actualExpressionWithoutGroups */
        $actualExpressionWithoutGroups = $visitor->getRoot();
        $expectedExpressionWithoutGroups = $this->createAlternationFromListOfStringSymbols(
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
            sprintf($assertFailMsgTpl, $this->serializeExpressionIterable($expectedExpressionWithoutGroups))
        );
    }

    /**
     * (a | (b | c | 1) | 2 | 3) => (a | b | c | 1 | 2 | 3)
     */
    public function testAlternationWithNestedAlternationAsSibling()
    {
        $listOfSymbols = $this->createListOfSymbols(array('b', 'c', '1'));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);
        $group       = new Group($alternation);

        $listOfSymbols = array(SymbolAdapter::createTerminal('a'), $group);
        $listOfSymbols = array_merge($listOfSymbols, $this->createListOfSymbols(array('2', '3')));
        $alternation = new Alternation(array_shift($listOfSymbols), $listOfSymbols);

        $visitor                    = new Converters\EliminateGroupsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker = new DepthFirstStackBasedWalker();
        $walker->walk($alternation, $hierarchicVisitDispatcher);

        /** @var Alternation $actualExpressionWithoutGroups */
        $actualExpressionWithoutGroups = $visitor->getRoot();
        $expectedExpressionWithoutGroups = $this->createAlternationFromListOfStringSymbols(
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
            sprintf($assertFailMsgTpl, $this->serializeExpressionIterable($expectedExpressionWithoutGroups))
        );
    }
}
