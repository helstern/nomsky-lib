<?php namespace Helstern\Nomsky\Lexers\EbnfLexer;

use Helstern\Nomsky\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenPattern\RegexAlternativesTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\RegexStringTokenPattern;

use Helstern\Nomsky\Lexers\EbnfLexer\SymbolsEnum as Symbols;
use Helstern\Nomsky\Lexers\EbnfLexer\TokenTypesEnum as TokenTypes;

class TokenPatterns
{
    /** @var RegexBuilder  */
    protected $regexBuilder;

    /**
     * @param TokenTypes $tokens
     * @throws \RuntimeException
     * @return array|AbstractRegexTokenPattern[]
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
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_START_COMMENT, Symbols::ENUM_START_COMMENT);
        $tokenPatterns[] = $instance->createQuotedPattern(TokenTypes::ENUM_END_COMMENT, Symbols::ENUM_END_COMMENT);
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
        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_SINGLE_QUOTE, Symbols::ENUM_SINGLE_QUOTE);
        $tokenPatterns[] = $instance->createStringPattern(TokenTypes::ENUM_DOUBLE_QUOTE, Symbols::ENUM_DOUBLE_QUOTE);
        $tokenPatterns[] = $instance->createQuotedPattern(
            TokenTypes::ENUM_SPECIAL_SEQUENCE,
            Symbols::ENUM_SPECIAL_SEQUENCE_DELIMITER
        );
        $tokenPatterns[] = $instance->buildLetterPattern(TokenTypes::ENUM_LETTER);
        $tokenPatterns[] = $instance->buildDecimalDigitPattern(TokenTypes::ENUM_DECIMAL_DIGIT);
        $tokenPatterns[] = $instance->buildIdSeparatorPattern(TokenTypes::ENUM_ID_SEPARATOR);

        $tokenPatterns[] = $instance->buildOtherCharacterPattern(TokenTypes::OTHER_CHARACTER);



        return $tokenPatterns;
    }

    public function __construct(RegexBuilder $regexBuilder)
    {
        $this->regexBuilder = $regexBuilder;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildIdSeparatorPattern($tokenType)
    {
        $pattern = $this->createStringPattern($tokenType, '_');
        return $pattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildOtherCharacterPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->pattern('.');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildDecimalDigitPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->characterRange('0', '9');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildLetterPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder->characterSet('\p{L}');
        $pattern = $this->createStringPattern($tokenType, (string) $regexBuilder);

        return $pattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildDefinitionListStartPattern($tokenType)
    {
        $alternatives = [
            Symbols::ENUM_DEFINE
            , Symbols::ENUM_DEFINE_ALT_ONE
        ];

        $tokenPattern = $this->createAlternativesTokenPattern($tokenType, $alternatives);
        return $tokenPattern;
    }


    //\(\*(?!\*\))?(?:.|\n|\r)*?\*\)

    /**
     * @param $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildTerminatorPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $alternatives = [
            (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR)->quote()
            , (string) $regexBuilder->pattern(Symbols::ENUM_TERMINATOR_ALT_ONE)->quote()
        ];

        $tokenPattern = $this->createAlternativesTokenPattern($tokenType, $alternatives);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildIdentifierPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;
        $stringPattern =  (string) $regexBuilder->sequence()
            ->add('[aA-zZ]')
            ->add(
            //(string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->group()->repeat()->group()
            (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->group()->repeatOnceOrMore()
        );

        $tokenPattern = $this->createStringPattern($tokenType, $stringPattern);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @param string $stringPattern
     * @throws \RuntimeException
     * @return RegexStringTokenPattern
     */
    protected function createStringPattern($tokenType, $stringPattern)
    {
        $tokenPattern = new RegexStringTokenPattern((int) $tokenType, $stringPattern);
        return $tokenPattern;
    }


    /**
     * @param int $tokenType
     * @param string $unquotedStringPattern
     * @throws \RuntimeException
     * @return RegexStringTokenPattern
     */
    protected function createQuotedPattern($tokenType, $unquotedStringPattern)
    {
        $regexBuilder = $this->regexBuilder;
        $quotedPattern = (string) $regexBuilder->pattern($unquotedStringPattern)->quote();

        $tokenPattern = new RegexStringTokenPattern((int) $tokenType, $quotedPattern);
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @param array $stringPatterns
     * @throws \RuntimeException
     * @return RegexAlternativesTokenPattern
     */
    protected function createAlternativesTokenPattern($tokenType, array $stringPatterns)
    {
        $tokenPattern = new RegexAlternativesTokenPattern((int) $tokenType, $stringPatterns);
        return $tokenPattern;
    }
}
