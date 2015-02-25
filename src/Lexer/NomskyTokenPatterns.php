<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\RegExBuilder\RegexBuilder;
use Helstern\Nomsky\Tokens\TokenPattern\RegexAlternativesTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\AbstractRegexTokenPattern;
use Helstern\Nomsky\Tokens\TokenPattern\RegexStringTokenPattern;

class NomskyTokenPatterns
{
    /** @var RegexBuilder  */
    protected $regexBuilder;

    /**
     * @param NomskyTokenTypeEnum $tokens
     * @throws \RuntimeException
     * @return array|AbstractRegexTokenPattern[]
     */
    static public function regexPatterns(NomskyTokenTypeEnum $tokens = null)
    {
        $regexBuilder = new RegexBuilder();
        $instance = new self($regexBuilder);

        if (is_null($tokens)) {
            $tokens = new NomskyTokenTypeEnum();
        }

        $tokenPatterns = [];

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_CONCATENATE)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(NomskyTokenTypeEnum::ENUM_CONCATENATE, ',');
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_CONCATENATE');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_DEFINITION_LIST_START)) {
            $tokenPatterns[] = $instance->buildDefinitionListStartPattern(
                NomskyTokenTypeEnum::ENUM_DEFINITION_LIST_START
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_DEFINITION_LIST_START');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_DEFINITION_SEPARATOR)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(
                NomskyTokenTypeEnum::ENUM_DEFINITION_SEPARATOR,
                $regexBuilder->pattern('|')->quote()->build()
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_DEFINITION_SEPARATOR');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_START_REPEAT)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(NomskyTokenTypeEnum::ENUM_START_REPEAT, '{');
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_START_REPEAT');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_END_REPEAT)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(NomskyTokenTypeEnum::ENUM_END_REPEAT, '}');
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_END_REPEAT');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_START_OPTION)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(
                NomskyTokenTypeEnum::ENUM_START_OPTION,
                $regexBuilder->pattern('[')->quote()->build()
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_START_OPTION');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_END_OPTION)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(
                NomskyTokenTypeEnum::ENUM_END_OPTION,
                $regexBuilder->pattern(']')->quote()->build()
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_END_OPTION');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_START_GROUP)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(
                NomskyTokenTypeEnum::ENUM_START_GROUP,
                $regexBuilder->pattern('(')->quote()->build()
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_START_GROUP');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_END_GROUP)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(
                NomskyTokenTypeEnum::ENUM_END_GROUP,
                $regexBuilder->pattern(')')->quote()->build()
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_END_GROUP');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_TERMINATOR)) {
            $tokenPatterns[] = $instance->createStringTokenPattern(
                NomskyTokenTypeEnum::ENUM_TERMINATOR,
                $regexBuilder->pattern('.')->quote()->build()
            );
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_TERMINATOR');
        }

//        if ($tokens->contains(NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE)) {
//            $tokenPatterns[] = $instance->buildSingleQuotePattern(NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE);
//        } else {
//            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_SINGLE_QUOTE');
//        }
//
//        if ($tokens->contains(NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE)) {
//            $tokenPatterns[] = $instance->buildDoubleQuotePattern(NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE);
//        } else {
//            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_DOUBLE_QUOTE');
//        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_CHARACTER_LITERAL)) {
            $tokenPatterns[] = $instance->buildCharacterLiteralPattern(NomskyTokenTypeEnum::ENUM_CHARACTER_LITERAL);
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_CHARACTER_LITERAL');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_STRING_LITERAL)) {
            $tokenPatterns[] = $instance->buildStringLiteralPattern(NomskyTokenTypeEnum::ENUM_STRING_LITERAL);
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_STRING_LITERAL');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_COMMENT_LITERAL)) {
            $tokenPatterns[] = $instance->buildCommentLiteralPattern(NomskyTokenTypeEnum::ENUM_COMMENT_LITERAL);
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_COMMENT_LITERAL');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_CHARACTER_RANGE)) {
            $tokenPatterns[] = $instance->buildCharacterRangePattern(NomskyTokenTypeEnum::ENUM_CHARACTER_RANGE);
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_CHARACTER_RANGE');
        }

        if ($tokens->contains(NomskyTokenTypeEnum::ENUM_IDENTIFIER)) {
            $tokenPatterns[] = $instance->buildIdentifierPattern(NomskyTokenTypeEnum::ENUM_IDENTIFIER);
        } else {
            throw new \RuntimeException('unknown token type NomskyTokenTypeEnum::TYPE_IDENTIFIER');
        }

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
    public function buildDefinitionListStartPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $tokenPattern = $this->createAlternativesTokenPattern(
            $tokenType,
            $regexBuilder->alternatives('=', ':==')->toList()
        );

        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildSingleQuotePattern($tokenType)
    {
        $pattern = $this->createStringTokenPattern($tokenType, "'");
        return $pattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildDoubleQuotePattern($tokenType)
    {
        $pattern = $this->createStringTokenPattern($tokenType, '"');
        return $pattern;
    }

    //\(\*(?!\*\))?(?:.|\n|\r)*?\*\)

    public function buildCommentLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $stringPattern = (string) $regexBuilder->sequence(
            (string) $regexBuilder->pattern('(*')->quote()
            , (string) $regexBuilder->negativeLookAhead('*)')->lazy()->quote()
            , (string) $regexBuilder->alternatives('.', '\n', '\r')->group()->repeatZeroOrMore()->lazy()
            , (string) $regexBuilder->pattern('*)')->quote()
        );

        $tokenPattern = $this->createStringTokenPattern($tokenType, $stringPattern);
        return $tokenPattern;

    }

    /**
     * @param int $tokenType
     * @return RegexAlternativesTokenPattern
     */
    public function buildCharacterLiteralPattern($tokenType)
    {
        $singleCharacter = $this->createSingleCharacterRegex();

        $tokenPattern = $this->createAlternativesTokenPattern(
            $tokenType,
            [
                (string) $singleCharacter->group()->delimit("'")
                , (string) $singleCharacter->group()->delimit('"')
            ]
        );

        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexAlternativesTokenPattern
     */
    public function buildStringLiteralPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $tokenPattern = $this->createAlternativesTokenPattern(
            $tokenType,
            [
                (string) $regexBuilder->sequence(
                    "'",
                    $regexBuilder->alternatives("[^']", "''")->group()->repeatOnceOrMore(),
                    "'"
                )
                , (string) $regexBuilder->alternatives('[^"]', '""')->group()->repeatOnceOrMore()->delimit('"')
            ]
        );
        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildCharacterRangePattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[[:punct:]]');

        $tokenPattern = $this->createStringTokenPattern(
            $tokenType,
            (string) $regexBuilder->sequence(
                (string) $singleCharacter->group()
                , $regexBuilder->pattern('..')->quote()->build()
                , (string) $singleCharacter->group()
            )
        );

        return $tokenPattern;
    }

    /**
     * @param int $tokenType
     * @return RegexStringTokenPattern
     */
    public function buildIdentifierPattern($tokenType)
    {
        $regexBuilder = $this->regexBuilder;

        $tokenPattern = $this->createStringTokenPattern(
            $tokenType,
            (string) $regexBuilder->sequence()
                ->add('[aA-zZ]')
                ->add(
                    //(string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->implode()->group()->repeat()->group()
                    (string) $regexBuilder->alternatives('[aA-zZ]', '[0-9]')->group()->repeatOnceOrMore()
                )
        );

        return $tokenPattern;
    }

    /**
     * @return \Helstern\Nomsky\RegExBuilder\RegexAlternativesBuilder
     */
    public function createSingleCharacterRegex()
    {
        $regexBuilder = $this->regexBuilder;
        $singleCharacter = $regexBuilder->alternatives('[a-z]', '[A-Z]', '[0-9]', '[[:punct:]]');

        return $singleCharacter;
    }

    /**
     * @param int $tokenType
     * @param string $stringPattern
     * @throws \RuntimeException
     * @return RegexStringTokenPattern
     */
    protected function createStringTokenPattern($tokenType, $stringPattern)
    {
        $tokenPattern = new RegexStringTokenPattern((int) $tokenType, $stringPattern);
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
