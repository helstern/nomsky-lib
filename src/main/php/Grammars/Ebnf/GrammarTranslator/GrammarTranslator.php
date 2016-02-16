<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslation;

use Helstern\Nomsky\Grammar\StandardGrammar;
use Helstern\Nomsky\Grammars\Ebnf\Ast\StringLiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslation\Translators\Translators;
use Helstern\Nomsky\Parser\Ast\StackBasedAstWalker;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingProvider;
use Helstern\Nomsky\Parser\AstNodeVisitStrategy\PreOrderVisitStrategy;

class GrammarTranslator
{
    public function translate(SyntaxNode $node)
    {
        $visitContext = new VisitContext();
        $visitorProvider = new Translators($visitContext);

        $dispatchingProvider = new DispatchingProvider($visitorProvider);
        $walkStrategy = PreOrderVisitStrategy::newDefaultInstance($dispatchingProvider);
        $walker = new StackBasedAstWalker($walkStrategy);
        $walker->walk($node);

        $productions = $visitContext->getProductions();
        if (empty($productions)) {
            throw new \Exception('no productions found');
        }

        $grammarTitle = 'untitled';
        $grammarTitleNode = $node->getGrammarTitleNode();
        if ($grammarTitleNode instanceof StringLiteralNode) {
            $grammarTitle = $grammarTitleNode->getLiteral();
        }

        $grammar = new StandardGrammar($grammarTitle, $productions);
        return $grammar;
    }
}
