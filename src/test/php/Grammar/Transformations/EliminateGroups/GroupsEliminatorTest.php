<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;

class GroupsEliminatorTest extends \PHPUnit_Framework_TestCase
{
    public function testVisitExpressionTreeOfOneLeaf()
    {
        $expression = ExpressionSymbol::createAdapterForTerminal('a');
        $eliminator = new Visitor();
        $eliminator->visitExpression($expression);

        $actual = $eliminator->getRoot();
        $this->assertEquals($expression, $actual);
    }

}
