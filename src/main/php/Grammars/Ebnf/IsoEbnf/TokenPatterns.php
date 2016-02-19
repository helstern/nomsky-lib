<?php namespace Helstern\Nomsky\Grammars\Ebnf\IsoEbnf;

use Helstern\Nomsky\Lexer\TokenMatchReader;
use Helstern\Nomsky\Text\RegexAlternativesPattern;
use Helstern\Nomsky\Text\RegexPattern;
use Helstern\Nomsky\Regex\RegexBuilder;
use Helstern\Nomsky\Text\TokenMatchPcreReader;

use Helstern\Nomsky\Grammars\Ebnf\IsoEbnf\TokenTypes as TokenTypes;

class TokenPatterns
{
    /** @var RegexBuilder  */
    protected $regexBuilder;

    /**
     * @param TokenTypes $tokens
     * @throws \RuntimeException
     * @return array|TokenMatchReader[]
     */
    static public function regexPatterns(TokenTypes $tokens = null)
    {
        if (is_null($tokens)) {
            $tokens = new TokenTypes();
        }

        $requiredTokens = array(
            TokenTypes::ENUM_CONCATENATE,
            TokenTypes::ENUM_DEFINITION_LIST_START,
            TokenTypes::ENUM_DEFINITION_SEPARATOR,
            TokenTypes::ENUM_START_REPEAT,
            TokenTypes::ENUM_END_REPEAT,
            TokenTypes::ENUM_START_OPTION,
            TokenTypes::ENUM_END_OPTION,
            TokenTypes::ENUM_START_GROUP,
            TokenTypes::ENUM_END_GROUP,
            TokenTypes::ENUM_TERMINATOR,
        );

        foreach ($requiredTokens as $requiredTokenType) {
            if (!$tokens->contains($requiredTokenType)) {
                throw new \RuntimeException('required token missing from enum');
            }
        }

        $regexBuilder = new RegexBuilder();
        $instance = new self($regexBuilder);
        //add longer patterns first
        $tokenPatterns = [];
        //longer patterns
        $tokenPatterns[] = $instance->buildStringLiteralPattern(TokenTypes::ENUM_STRING_LITERAL);
        $tokenPatterns[] = $instance->buildIdentifierPattern(TokenTypes::ENUM_IDENTIFIER);
        $tokenPatterns[] = $instance->buildSpecialSequencePattern(TokenTypes::ENUM_SPECIAL_SEQUENCE);
        $tokenPatterns[] = $instance->buildCommentPattern(TokenTypes::ENUM_COMMENT);
        $tokenPatterns[] = $instance->buildDefinitionListStartPattern(TokenTypes::ENUM_DEFINITION_LIST_START);
        //shorter patterns
        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_CONCATENATE, Symbols::ENUM_CONCATENATE);
        $tokenPatterns[] = $instance->createQuotedPattern(
            TokenTypes::ENUM_DEFINITION_SEPARATOR,
            Symbols::ENUM_DEFINITION_SEPARATOR
        );
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_REPEAT, Symbols::ENUM_START_REPEAT);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_REPEAT, Symbols::ENUM_END_REPEAT);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_OPTION, Symbols::ENUM_START_OPTION);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_OPTION, Symbols::ENUM_END_OPTION);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_GROUP, Symbols::ENUM_START_GROUP);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_GROUP, Symbols::ENUM_END_GROUP);
        $tokenPatterns[] = $instance->buildTerminatorPattern(TokenTypes::ENUM_TERMINATOR);

        return $tokenPatterns;
    }

    /**
     * @param RegexBuilder $regexBuilder
     */
    public function __construct(RegexBuilder $regexBuilder)
    {
        $this->regexBuilder = $regexBuilder;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildCommentPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $emptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_START_COMMENT)->quote()
            , (string) $regexBuilder->pattern('\s')->repeatZeroOrMore()
            , (string) $regexBuilder->pattern(Symbols::ENUM_END_COMMENT)->quote()
        );

        $nonEmptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_START_COMMENT)->quote()
            , (string) $regexBuilder->negativeLookAhead(Symbols::ENUM_END_COMMENT)->quote()
            , (string) $regexBuilder->alternatives('.', '\n', '\r')->nonCapturingGroup()->repeatZeroOrMore()->lazy()
            , (string) $regexBuilder->pattern(Symbols::ENUM_END_COMMENT)->quote()
        );

        $stringPattern = (string) $regexBuilder->alternatives(
            (string) $emptyCommentPattern
            , (string) $nonEmptyCommentPattern
        )->nonCapturingGroup();

        $pcreMatcher = (new RegexPattern($stringPattern))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildIdentifierPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern =  (string) $regexBuilder->sequence()
            ->add('[aA-zZ]')
            ->add(
            //(string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->group()->repeat()->group()
                (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]', '[-]')->nonCapturingGroup()->repeatOnceOrMore()
            );

        $pcreMatcher = (new RegexPattern($stringPattern))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildCharLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern =  (string) $regexBuilder->sequence(
            Symbols::ENUM_SINGLE_QUOTE,
            (string) $regexBuilder->alternatives(
                '\p{L}|\s',
                str_repeat(Symbols::ENUM_SINGLE_QUOTE, 2)
            )
            ->nonCapturingGroup(),
            Symbols::ENUM_SINGLE_QUOTE
        );

        $pcreMatcher = (new RegexPattern($stringPattern))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildStringLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $alternatives = [
            (string) $regexBuilder->sequence(
                Symbols::ENUM_SINGLE_QUOTE,
                (string) $regexBuilder->alternatives("[^']", "''")
                    ->nonCapturingGroup()
                    ->repeatOnceOrMore(),
                Symbols::ENUM_SINGLE_QUOTE
            )
            , (string) $regexBuilder->alternatives('[^"]', '""')
                ->nonCapturingGroup()
                ->repeatOnceOrMore()
                ->delimit(Symbols::ENUM_DOUBLE_QUOTE)
        ];

        $pcreMatcher = (new RegexAlternativesPattern($alternatives))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildSpecialSequencePattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $emptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
            , (string) $regexBuilder->pattern('\s')->repeatZeroOrMore()
            , (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
        );

        $nonEmptyCommentPattern = $regexBuilder->sequence(
            (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
            , (string) $regexBuilder->negativeLookAhead(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
            , (string) $regexBuilder->pattern(Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER)->quote()
        );

        $stringPattern = (string) $regexBuilder->alternatives(
            (string) $emptyCommentPattern
            , (string) $nonEmptyCommentPattern
        )->nonCapturingGroup();

        $pcreMatcher = (new RegexPattern($stringPattern))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildDecimalDigitPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->characterRange('0', '9');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildLetterPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->characterSet('\p{L}');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
    }

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildDefinitionListStartPattern($tokenType)
    {
        $alternatives = [
            Symbols::ENUM_DEFINE
            , Symbols::ENUM_DEFINE_ALT_ONE
        ];

        $pcreMatcher = (new RegexAlternativesPattern($alternatives))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }


    //\(\*(?!\*\))?(?:.|\n|\r)*?\*\)

    /**
     * @param string $tokenType
     *
     * @return TokenMatchPcreReader
     */
    public function buildTerminatorPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $alternatives = [
            (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR)->quote()
            , (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR_ALT_ONE)->quote()
        ];

        $pcreMatcher = (new RegexAlternativesPattern($alternatives))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }

    /**
     * @param string $tokenType
     * @param string $stringPattern
     *
     * @return TokenMatchPcreReader
     * @throws \RuntimeException
     */
    protected function createStringPattern($tokenType, $stringPattern)
    {
        $pcreMatcher = (new RegexPattern($stringPattern))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);

        return $matcher;
    }

    /**
     * @param string $tokenType
     * @param string $unquotedStringPattern
     *
     * @return TokenMatchPcreReader
     * @throws \RuntimeException
     */
    protected function createQuotedPattern($tokenType, $unquotedStringPattern)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern = (string) $regexBuilder->pattern($unquotedStringPattern)->quote();

        $pcreMatcher = (new RegexPattern($stringPattern))->anchorAtStart()->dotAll()->utf8()->matchOne();
        $matcher = new TokenMatchPcreReader($tokenType, $pcreMatcher);
        return $matcher;
    }
}
