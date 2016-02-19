<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar;

use Helstern\Nomsky\Grammar\StandardGrammar;
use Helstern\Nomsky\Grammars\Ebnf\Ast\LiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors\VisitorProvider;
use Helstern\Nomsky\Parser\Ast\StackBasedAstWalker;
use Helstern\Nomsky\Parser\AstNodeVisitor\DispatchingProvider;
use Helstern\Nomsky\Parser\AstNodeVisitStrategy\PreOrderVisitStrategy;

class AstTranslator
{
    public function translate(SyntaxNode $node)
    {
        $visitContext = new AstTranslatorContext();
        $visitorProvider = new VisitorProvider($visitContext);

        $dispatchingProvider = new DispatchingProvider($visitorProvider);
        $walkStrategy = PreOrderVisitStrategy::newDefaultInstance($dispatchingProvider);
        $walker = new StackBasedAstWalker($walkStrategy);
        foreach ($node->getRuleNodes() as $rule) {
            $walker->walk($rule);
        }

        $productions = $visitContext->getProductions();
        if (empty($productions)) {
            throw new \Exception('no productions found');
        }

        $grammarTitle = 'untitled';
        $grammarTitleNode = $node->getGrammarTitleNode();
        if ($grammarTitleNode instanceof LiteralNode) {
            $grammarTitle = $grammarTitleNode->getLiteral();
        }

        $grammar = new StandardGrammar($grammarTitle, $productions);
        return $grammar;
    }
}
