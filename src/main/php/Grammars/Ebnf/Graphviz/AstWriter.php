<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz;

use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors\Writers;
use Helstern\Nomsky\Graphviz\DotFile;
use Helstern\Nomsky\Parser\Ast\StackBasedAstWalker;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingProvider;
use Helstern\Nomsky\Parser\AstNodeVisitStrategy\PreOrderVisitStrategy;

class AstWriter
{
    /**
     * @param SyntaxNode $astNode
     * @param DotFile $dotFile
     *
     * @return bool
     */
    public function write(SyntaxNode $astNode, DotFile $dotFile)
    {
        $visitorProvider = new Writers($dotFile, new VisitContext());
        $dispatchingProvider = new DispatchingProvider($visitorProvider);

        $walkStrategy = PreOrderVisitStrategy::newDefaultInstance($dispatchingProvider);
        $walker = new StackBasedAstWalker($walkStrategy);

        $walkResult = $walker->walk($astNode);
        return $walkResult;
    }
}
