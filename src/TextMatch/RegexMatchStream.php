<?php namespace Helstern\Nomsky\TextMatch;

class RegexMatchStream
{
    /** @var array[] */
    protected $matches;

    /** @var string */
    protected $originalText;

    /** @var int */
    protected $matchIndex = -1;

    /** @var CharacterPosition */
    protected $previousPosition;

    /** @var CharacterPosition */
    protected $offsetPosition;

    /**
     * @param string $pregMatchPattern
     * @param string $originalText
     */
    public function __construct($pregMatchPattern, $originalText)
    {
        $tokenMatches = array();
        preg_match_all($pregMatchPattern, $originalText, $tokenMatches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        $tokenMatchFilter = $this->getMatchCleaner();
        $this->matches = array_map($tokenMatchFilter, $tokenMatches);

        $this->matchIndex = 0;
        $this->previousPosition = new CharacterPosition(0, 0, 0);
    }

    /**
     * @return callable|\Closure
     */
    protected function getMatchCleaner()
    {
        $filter = function (array $tokenMatch) {
            if ($tokenMatch[1] == -1) {
                return false;
            }
            return true;
        };

        $mapper = function(array $match) use ($filter) {
            return array_filter($match, $filter);
        };

        return $mapper;
    }

    /**
     * @return array|null
     */
    public function current()
    {
        if ($this->valid()) {
            return $this->matches[0];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if ($this->matchIndex < count($this->matches)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function next()
    {
        if ($this->valid()) {
            $this->previousPosition = $this->position();
            $this->offsetPosition = null;

            $this->matchIndex++;
        } else {
            return false;
        }

        if ($this->valid()) {
            return true;
        }

        return false;
    }

    /**
     * @return null|CharacterPosition
     */
    public function position()
    {
        if (!$this->valid()) {
            return null;
        }

        if (is_null($this->offsetPosition)) {
            $this->offsetPosition = $this->calculateOffsetPosition();
        }

        return $this->previousPosition->offsetRight($this->offsetPosition);
    }

    /**
     * @return CharacterPosition
     */
    protected function calculateOffsetPosition()
    {
        $match = $this->current();
        $matchByteIndex = $match[0][1];
        $offsetByte = $matchByteIndex - $this->previousPosition->getByteIndex();

        $text = substr($this->originalText, $this->previousPosition->getByteIndex(), $offsetByte);

        $newLineMatches = array();
        $offsetLines = (int) preg_match("#\r\n|\n|\r#m", $text, $newLineMatches, PREG_OFFSET_CAPTURE);

        if ($offsetLines > 0) {
            $lastMatchByteIndex = end($newLineMatches)[1];
            $text = substr($text, $lastMatchByteIndex);
        }
        $offsetColumn = mb_strlen($text, 'UTF-8');

        $offsetPosition = new CharacterPosition($offsetByte, $offsetColumn, $offsetLines);
        return $offsetPosition;
    }
}
