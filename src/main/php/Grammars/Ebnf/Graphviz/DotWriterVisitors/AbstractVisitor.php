<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;

abstract class AbstractVisitor
{
    protected function buildNumberedDOTIdentifier($sprintfPattern, VisitContext $visitContext)
    {
        $idNumber = $visitContext->getNodeCount();
        return sprintf($sprintfPattern, $idNumber);
    }
}
