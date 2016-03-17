<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateOptionals;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;

class OptionalsEliminatorTest extends \PHPUnit_Framework_TestCase
{
    public function testVisitExpressionTreeOfOneLeaf()
    {
        $expression = ExpressionSymbol::createAdapterForTerminal('a');
        $eliminator = new OptionalsEliminator(new IncrementalNamingStrategy());
        $eliminator->visitExpression($expression);

        $actual = $eliminator->getRoot();
        $this->assertEquals($expression, $actual);
    }

}
