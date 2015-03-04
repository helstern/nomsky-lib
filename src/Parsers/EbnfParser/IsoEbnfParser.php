<?php namespace Helstern\Nomsky\Parsers\EbnfParser;


use Helstern\Nomsky\Exception\SyntacticException;
use Helstern\Nomsky\Parsers\EbnfAst\AlternativeNode;
use Helstern\Nomsky\Parsers\AstNode;
use Helstern\Nomsky\Parsers\EbnfAst\GroupedExpressionNode;
use Helstern\Nomsky\Parsers\EbnfAst\IdentifierNode;
use Helstern\Nomsky\Parsers\EbnfAst\LiteralNode;
use Helstern\Nomsky\Parsers\EbnfAst\RepeatedExpressionNode;
use Helstern\Nomsky\Parsers\EbnfAst\OptionalExpressionNode;
use Helstern\Nomsky\Parsers\EbnfAst\ProductionNode;
use Helstern\Nomsky\Parsers\EbnfAst\SequenceNode;
use Helstern\Nomsky\Parsers\EbnfAst\SyntaxNode;
use Helstern\Nomsky\Parser\ParseAssertion\TokenAssertions;
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
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertNotEOF('Empty grammar', $token);

        $startTextPosition = null;
        $grammarTitle = null;
        $grammarComment = null;

        $actualType = $token->getType();
        $expectedTypes = array(TokenTypesEnum::ENUM_SINGLE_QUOTE, TokenTypesEnum::ENUM_DOUBLE_QUOTE);
        if (in_array($actualType, $expectedTypes)) {
            $startTextPosition = $token->getPosition();
            $grammarTitle = $this->parseStringExpression($lexer);
            $lexer->nextToken();
        }

        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_START_REPEAT);
        if (is_null($startTextPosition)) {
            $startTextPosition = $token->getPosition();
        }
        $lexer->nextToken();

        $productionNodes = array();
        $token = $lexer->currentToken();
        while ($token->getType() == TokenTypesEnum::ENUM_START_COMMENT) {

            $productionNodes[] = $this->parseRuleComment($lexer);
            $lexer->nextToken();
            $token = $lexer->currentToken();
        }

        while ($token->getType() == TokenTypesEnum::ENUM_LETTER) {
            $productionNodes[] = $this->parseRule($lexer);
            $lexer->nextToken();
            $token = $lexer->currentToken();

            if ($token->getType() == TokenTypesEnum::ENUM_START_COMMENT) {
                $productionNodes[] = $this->parseRuleComment($lexer);
                $lexer->nextToken();
                $token = $lexer->currentToken();
            }
        }

        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_END_REPEAT);
        $lexer->nextToken();
        $token = $lexer->currentToken();

        $actualType = $token->getType();
        $expectedTypes = array(TokenTypesEnum::ENUM_SINGLE_QUOTE, TokenTypesEnum::ENUM_DOUBLE_QUOTE);
        if (in_array($actualType, $expectedTypes)) {
            $grammarComment = $this->parseStringExpression($lexer);
            $lexer->nextToken();
            $token = null;
        }

        $syntaxNode = new SyntaxNode($startTextPosition, $productionNodes, $grammarTitle, $grammarComment);
        return $syntaxNode;
    }

    /**
     * @param Lexer $lexer
     * @return LiteralNode
     * @throws \Helstern\Nomsky\Exception\SyntacticException
     */
    protected function parseRuleComment(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_START_COMMENT);
        $textPosition = $token->getPosition();
        $comment = '';

        $peekToken = $lexer->peekToken();
        while (!is_null($peekToken) && $peekToken->getType() != TokenTypesEnum::ENUM_END_COMMENT) {
            $comment .= $peekToken->getValue();

            $peekToken = null;
            if ($lexer->nextToken()) {
                $peekToken = $lexer->peekToken();
            }
        }

        if (is_null($peekToken)) {
            throw new SyntacticException('un-terminated comment');
        } else {
            $lexer->nextToken();
        }

        $node = new LiteralNode($textPosition, $comment);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return ProductionNode
     */
    protected function parseRule(Lexer $lexer)
    {
        $identifierNode = $this->parseIdentifier($lexer);

        $peekToken = $lexer->peekToken();
        $this->tokenAssertions->assertSameType('expected token', $peekToken, TokenTypesEnum::ENUM_DEFINITION_LIST_START);

        $lexer->nextToken();
        $expressionNode = $this->parseExpression($lexer);

        $peekToken = $lexer->peekToken();
        $this->tokenAssertions->assertSameType('expected token', $peekToken, TokenTypesEnum::ENUM_TERMINATOR);
        $lexer->nextToken();

        $textPosition = $identifierNode->getTextPosition();
        $node = new ProductionNode($textPosition, $identifierNode, $expressionNode);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @throws SyntacticException
     * @return IdentifierNode
     */
    protected function parseIdentifier(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_LETTER);
        $identifierName = $token->getValue();
        $textPosition = $token->getPosition();

        $peekToken = $lexer->peekToken();

        $actualTokenType = $peekToken->getType();
        $expectedTokenTypes = [
            TokenTypesEnum::ENUM_LETTER,
            TokenTypesEnum::ENUM_DECIMAL_DIGIT,
            TokenTypesEnum::ENUM_ID_SEPARATOR
        ];

        while (!is_null($peekToken) && in_array($actualTokenType, $expectedTokenTypes)) {
            $identifierName .= $peekToken->getValue();

            $peekToken = null;
            if ($lexer->nextToken()) {
                $peekToken = $lexer->peekToken();
                $actualTokenType = $peekToken->getType();
            }
        }

        if (is_null($peekToken)) {
            throw new SyntacticException('unexpected eof');
        } else {
            $lexer->nextToken();
        }


        $node = new IdentifierNode($textPosition, $identifierName);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return AlternativeNode|GroupedExpressionNode|IdentifierNode|LiteralNode|RepeatedExpressionNode|OptionalExpressionNode|SequenceNode
     */
    protected function parseExpression(Lexer $lexer)
    {
        $head = $this->parseTerm($lexer);
        $tail = array();

        $peekToken = $lexer->peekToken();
        $predicates = $this->tokenAssertions->getPredicates();
        while ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_DEFINITION_SEPARATOR)) {
            $lexer->nextToken();
            $tail[] = $this->parseTerm($lexer);

            $peekToken = $lexer->peekToken();
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
     * @return GroupedExpressionNode|IdentifierNode|LiteralNode|RepeatedExpressionNode|OptionalExpressionNode|SequenceNode
     */
    protected function parseTerm(Lexer $lexer)
    {
        //first factor
        $head = $this->parseFactor($lexer);

        $tail = array();
        $predicates = $this->tokenAssertions->getPredicates();
        $peekToken = $lexer->peekToken();
        while ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_CONCATENATE)) {
            $lexer->nextToken();
            $tail[] = $this->parseFactor($lexer);

            $peekToken = $lexer->peekToken();
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
     * @return GroupedExpressionNode|IdentifierNode|LiteralNode|RepeatedExpressionNode|OptionalExpressionNode
     * @throws \Exception
     */
    protected function parseFactor(Lexer $lexer)
    {
        $predicates = $this->tokenAssertions->getPredicates();
        $peekToken = $lexer->peekToken();

        /** @var AstNode $node */
        $node = null;
        if ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_LETTER)) {
            $lexer->nextToken();
            $node = $this->parseIdentifier($lexer);
        } elseif ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_SINGLE_QUOTE)) {
            $lexer->nextToken();
            $node = $this->parseStringExpression($lexer);
        } elseif ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_DOUBLE_QUOTE)) {
            $lexer->nextToken();
            $node = $this->parseStringExpression($lexer);
        } elseif ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_SPECIAL_SEQUENCE)) {
            $lexer->nextToken();
            $node = $this->parseSpecialExpression($lexer);
        } elseif ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_START_OPTION)) {
            $lexer->nextToken();
            $node = $this->parseOptionalExpression($lexer);
        } elseif ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_START_GROUP)) {
            $lexer->nextToken();
            $node = $this->parseGroupedExpression($lexer);
        } elseif ($predicates->hasSameType($peekToken, TokenTypesEnum::ENUM_START_REPEAT)) {
            $lexer->nextToken();
            $node = $this->parseRepeatedExpression($lexer);
        }

        if (is_null($node)) {
            throw new \Exception('boo');
        }

        return $node;
    }

    protected function parseStringExpression(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $actualTokenType = $token->getType();

        $expectedTokenTypes = array(TokenTypesEnum::ENUM_SINGLE_QUOTE, TokenTypesEnum::ENUM_DOUBLE_QUOTE);
        if (!in_array($actualTokenType, $expectedTokenTypes)) {
            throw new SyntacticException($token, 'Expected ' . implode($expectedTokenTypes));
        }

        $textPosition = $token->getPosition();
        $peekToken = $lexer->peekToken();

        $literal = '';
        while (false == is_null($peekToken) && $peekToken->getType() !== $actualTokenType) {
            $literal .= $peekToken->getValue();

            $peekToken = null;
            if ($lexer->nextToken()) {
                $peekToken = $lexer->peekToken();
            }
        }

        if (is_null($peekToken)) {
            throw new SyntacticException('un-enclosed string');
        } else {
            $lexer->nextToken();
        }

        $node = new LiteralNode($textPosition, $literal);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @throws SyntacticException
     * @return LiteralNode
     */
    protected function parseSpecialExpression(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_SPECIAL_SEQUENCE);
        $startPosition = $token->getPosition();

        $specialSequence = '';
        $peekToken = $lexer->peekToken();
        $peekTokenType = $peekToken->getType();

        while (!is_null($peekToken) && $peekTokenType != TokenTypesEnum::ENUM_SPECIAL_SEQUENCE) {
            $specialSequence .= $peekToken->getValue();

            $peekToken = null;
            if ($lexer->nextToken()) {
                $peekToken = $lexer->peekToken();
                $peekTokenType = $peekToken->getType();
            }
        }

        if (is_null($peekToken)) {
            throw new SyntacticException('un-enclosed special sequence');
        } else {
            $lexer->nextToken();
        }

        $node = new LiteralNode($startPosition, $specialSequence);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return OptionalExpressionNode
     */
    protected function parseOptionalExpression(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_START_OPTION);
        $startPosition = $token->getPosition();

        $expression = $this->parseExpression($lexer);

        $peekToken = $lexer->peekToken();
        $this->tokenAssertions->assertSameType('expected token', $peekToken, TokenTypesEnum::ENUM_END_OPTION);
        $lexer->nextToken();

        $node = new OptionalExpressionNode($startPosition, $expression);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return OptionalExpressionNode
     */
    protected function parseGroupedExpression(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_START_GROUP);
        $startPosition = $token->getPosition();

        $expression = $this->parseExpression($lexer);

        $peekToken = $lexer->peekToken();
        $this->tokenAssertions->assertSameType('expected token', $peekToken, TokenTypesEnum::ENUM_END_GROUP);
        $lexer->nextToken();

        $node = new GroupedExpressionNode($startPosition, $expression);
        return $node;
    }

    /**
     * @param Lexer $lexer
     * @return OptionalExpressionNode
     */
    protected function parseRepeatedExpression(Lexer $lexer)
    {
        $token = $lexer->currentToken();
        $this->tokenAssertions->assertSameType('expected token', $token, TokenTypesEnum::ENUM_START_REPEAT);
        $startPosition = $token->getPosition();

        $expression = $this->parseExpression($lexer);

        $peekToken = $lexer->peekToken();
        $this->tokenAssertions->assertSameType('expected token', $peekToken, TokenTypesEnum::ENUM_END_REPEAT);
        $lexer->nextToken();

        $node = new RepeatedExpressionNode($startPosition, $expression);
        return $node;
    }
}
