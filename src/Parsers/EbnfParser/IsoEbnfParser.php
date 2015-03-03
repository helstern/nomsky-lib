<?php namespace Helstern\Nomsky\Parsers\EbnfParser;


use Helstern\Nomsky\Parsers\EbnfAst\AlternativeNode;
use Helstern\Nomsky\Parsers\AstNode;
use Helstern\Nomsky\Parsers\EbnfAst\GroupNode;
use Helstern\Nomsky\Parsers\EbnfAst\IdentifierNode;
use Helstern\Nomsky\Parsers\EbnfAst\LiteralNode;
use Helstern\Nomsky\Parsers\EbnfAst\OptionalExpressionListNode;
use Helstern\Nomsky\Parsers\EbnfAst\OptionalExpressionNode;
use Helstern\Nomsky\Parsers\EbnfAst\ProductionNode;
use Helstern\Nomsky\Parsers\EbnfAst\SequenceNode;
use Helstern\Nomsky\Parsers\EbnfAst\SyntaxNode;
use Helstern\Nomsky\ParseAssertion\TokenAssertions;
use Helstern\Nomsky\Parser\Lexer;

use Helstern\Nomsky\Lexers\EbnfLexer\TokenTypesEnum;

/**
 * Class StandardEbnfParser
 * @see http://standards.iso.org/ittf/PubliclyAvailableStandards/s026153_ISO_IEC_14977_1996(E).zip
 */
class IsoEbnfParser
{
    /** @var TokenAssertions */
    protected $tokenAssertions;

    /**
     * @param TokenAssertions $tokenAssertions
     */
    public function __construct(TokenAssertions $tokenAssertions)
    {
        $this->tokenAssertions = $tokenAssertions;
    }

    /**
     * @param Lexer $lexer
     * @return SyntaxNode
     */
    public function parse(Lexer $lexer)
    {
        $astNode = $this->parseSyntax($lexer);
        return $astNode;
    }

    /**
     * @param Lexer $lexer
     * @return SyntaxNode
     */
    protected function parseSyntax(Lexer $lexer)
    {
        $lexer->nextToken();
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertNotEOF('Empty grammar', $token);

        $startTextPosition = $token->getPosition();

        /** @var string $grammarTitle */
        $grammarTitle = null;
        $tokenPredicates = $this->tokenAssertions->getPredicates();
        if ($tokenPredicates->hasSameType($token, TokenTypesEnum::ENUM_LITERAL)) {
            $grammarTitle = $token->getValue();

            $lexer->nextToken();
        }

        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_START_REPEAT);

        $productionNodes = array();
        $token = $lexer->peekToken();
        while ($tokenPredicates->hasSameType($token, TokenTypesEnum::ENUM_IDENTIFIER)) {
            $productionNode = $this->parseProduction($lexer);
            $productionNodes[] = $productionNode;

            $token = $lexer->peekToken();
        }

        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_END_REPEAT);

        $grammarComment = null;
        $lexer->nextToken();
        $token = $lexer->currentToken();
        $tokenPredicates = $this->tokenAssertions->getPredicates();
        if ($tokenPredicates->hasSameType($token, TokenTypesEnum::ENUM_LITERAL)) {
            $grammarComment = $token->getValue();
        }

        $syntaxNode = new SyntaxNode($startTextPosition, $productionNodes, $grammarTitle, $grammarComment);
        return $syntaxNode;
    }

    /**
     * @param Lexer $lexer
     * @return ProductionNode
     */
    protected function parseProduction(Lexer $lexer)
    {
        $identifierNode = $this->parseIdentifier($lexer);

        $lexer->nextToken();
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_DEFINITION_LIST_START);

        $expressionNode = $this->parseExpression($lexer);

        $lexer->nextToken();
        $token = $lexer->currentToken();

        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_TERMINATOR);

        $textPosition = $identifierNode->getTextPosition();
        $node = new ProductionNode($textPosition, $identifierNode, $expressionNode);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return IdentifierNode
     */
    protected function parseIdentifier(Lexer $lexer)
    {
        $lexer->nextToken();
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_IDENTIFIER);

        $identifierName = $token->getValue();
        $textPosition = $token->getPosition();

        $node = new IdentifierNode($textPosition, $identifierName);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return AlternativeNode|GroupNode|IdentifierNode|LiteralNode|OptionalExpressionListNode|OptionalExpressionNode|SequenceNode
     */
    protected function parseExpression(Lexer $lexer)
    {
        $head = $this->parseTerm($lexer);
        $tail = array();

        $predicates = $this->tokenAssertions->getPredicates();
        $token = $lexer->peekToken();
        while ($predicates->hasSameType($token, TokenTypesEnum::ENUM_DEFINITION_SEPARATOR)) {
            $tail[] = $this->parseTerm($lexer);
            $token = $lexer->peekToken();
        }

        if (empty($tail)) {
            return $head;
        }

        $textPosition = $head->getTextPosition();
        $node = new AlternativeNode($textPosition, $head, $tail);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return GroupNode|IdentifierNode|LiteralNode|OptionalExpressionListNode|OptionalExpressionNode|SequenceNode
     */
    protected function parseTerm(Lexer $lexer)
    {
        //first factor
        $head = $this->parseFactor($lexer);

        $predicates = $this->tokenAssertions->getPredicates();
//        $token = $tokenScanner->peekToken();
//        while ($predicates->hasSameType($token, NomskyTokenTypeEnum::TYPE_WS)) {
//            $tokenScanner->nextToken();
//            $token = $tokenScanner->peekToken();
//        }

        $tail = array();
        $token = $lexer->peekToken();
        while (
            $predicates->hasAnyType($token, array(
                TokenTypesEnum::ENUM_IDENTIFIER
                , TokenTypesEnum::ENUM_LITERAL
                , TokenTypesEnum::ENUM_START_REPEAT
                , TokenTypesEnum::ENUM_START_OPTION
                , TokenTypesEnum::ENUM_START_GROUP
            ))
        ) {
            $tail[] = $this->parseFactor($lexer);
            $token = $lexer->peekToken();
        }

        if (empty($tail)) {
            return $head;
        }

        $textPosition = $head->getTextPosition();
        $node = new SequenceNode($textPosition, $head, $tail);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return GroupNode|IdentifierNode|LiteralNode|OptionalExpressionListNode|OptionalExpressionNode
     * @throws \Exception
     */
    protected function parseFactor(Lexer $lexer)
    {
        $predicates = $this->tokenAssertions->getPredicates();

        $token = $lexer->peekToken();
        if ($predicates->hasSameType($token, TokenTypesEnum::ENUM_WS)) {
            $lexer->nextToken();
            $token = $lexer->peekToken();
        }

        /** @var AstNode $node */
        $node = null;
        if ($predicates->hasSameType($token, TokenTypesEnum::ENUM_IDENTIFIER)) {
            $node = $this->parseIdentifier($lexer);
        } elseif ($predicates->hasSameType($token, TokenTypesEnum::ENUM_LITERAL)) {
            $lexer->nextToken();
            $token = $lexer->currentToken();

            $node = new LiteralNode($token->getPosition(), $token->getValue());
        } elseif ($predicates->hasSameType($token, TokenTypesEnum::ENUM_START_REPEAT)) {
            $startAtTextPosition = $token->getPosition();
            $lexer->nextToken();
            $expression = $this->parseExpression($lexer);

            $lexer->nextToken();
            $token = $lexer->currentToken();
            $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_END_REPEAT);

            $node = new OptionalExpressionListNode($startAtTextPosition, $expression);
        } elseif ($predicates->hasSameType($token, TokenTypesEnum::ENUM_START_OPTION)) {
            $startAtTextPosition = $token->getPosition();
            $lexer->nextToken();
            $expression = $this->parseExpression($lexer);

            $lexer->nextToken();
            $token = $lexer->currentToken();
            $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_END_OPTION);

            $node = new OptionalExpressionNode($startAtTextPosition, $expression);
        } elseif ($predicates->hasSameType($token, TokenTypesEnum::ENUM_START_GROUP)) {
            $startAtTextPosition = $token->getPosition();
            $lexer->nextToken();
            $expression = $this->parseExpression($lexer);

            $lexer->nextToken();
            $token = $lexer->currentToken();
            $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_END_GROUP);

            $node = new GroupNode($startAtTextPosition, $expression);
        }

        if (is_null($node)) {
            throw new \Exception('boo');
        }

        return $node;
    }
}
