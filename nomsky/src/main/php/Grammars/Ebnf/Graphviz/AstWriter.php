<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz;

use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors\Visitors;
use Helstern\Nomsky\Graphviz\LocalDotFile;
use Helstern\Nomsky\Parser\Ast\StackBasedAstWalker;
use Helstern\Nomsky\Parser\AstNodeWalkStrategy\PreOrderWalkStrategy;

class AstWriter
{
    /**
     * @param SyntaxNode $astNode
     * @param \SplFileInfo $fileInfo
     * @return bool
     */
    public function write(SyntaxNode $astNode, \SplFileInfo $fileInfo)
    {
        $dotFile = new LocalDotFile($fileInfo);
        $visitorProvider = new Visitors($dotFile);

        $walkStrategy = new PreOrderWalkStrategy($visitorProvider);
        $walker = new StackBasedAstWalker($walkStrategy);

        $walkResult = $walker->walk($astNode);
        return $walkResult;
    }
}
