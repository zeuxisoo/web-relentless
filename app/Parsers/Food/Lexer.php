<?php
namespace App\Parsers\Food;

use App\Parsers\Food\Exceptions\LexerException;
use App\Parsers\Food\Traits\Patterns\CommonPattern;
use App\Parsers\Food\Traits\Patterns\ComposePattern;
use App\Parsers\Food\Traits\Patterns\DateTimePattern;
use App\Parsers\Food\Traits\Patterns\LiteralPattern;
use App\Parsers\Food\Traits\Patterns\NumberPattern;
use App\Parsers\Food\Traits\Patterns\SymbolPattern;
use App\Parsers\Food\Traits\Patterns\TagPattern;

class Lexer {

    use CommonPattern, SymbolPattern, NumberPattern, ComposePattern, DateTimePattern, TagPattern, LiteralPattern;

    protected int $contentLength;
    protected int $currentPosition;
    protected int $currentLine;
    protected int $currentColumn;

    public function __construct(
        public string $content
    ) {
        $this->contentLength   = mb_strlen($content);
        $this->currentPosition = 0;
        $this->currentLine     = 1;
        $this->currentColumn   = 0;
    }

    public function lex(): array {
        $tokens      = [];
        $currentChar = "";

        while($this->currentPosition < $this->contentLength) {
            $this->skipNewlineOrWhiteSpace();

            $currentChar = $this->readChar();

            if ($this->isSlash($currentChar) && $this->isSlash($this->lookChar())) {
                $this->skipSingleComment();
                continue;
            }

            if ($this->isDigit($currentChar)) {
                $numberOrDateOrTime = $this->readNumberOrDateOrTime($currentChar);

                if ($this->isDate($numberOrDateOrTime)) {
                    $tokens[] = $this->addToken(TokenKind::Date, $numberOrDateOrTime);
                    continue;
                }

                if ($this->isTime($numberOrDateOrTime)) {
                    $tokens[] = $this->addToken(TokenKind::Time, $numberOrDateOrTime);
                    continue;
                }

                if ($this->isInteger($numberOrDateOrTime)) {
                    $tokens[] = $this->addToken(TokenKind::Integer, $numberOrDateOrTime);
                    continue;
                }

                if ($this->isDouble($numberOrDateOrTime)) {
                    $tokens[] = $this->addToken(TokenKind::Double, $numberOrDateOrTime);
                    continue;
                }
            }

            if ($this->isSharp($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::Sharp, $currentChar);

                $lookChar = $this->lookChar();

                if ($this->isTag($lookChar)) {
                    $currentChar = $this->readChar();
                    $literal     = $this->readTag($currentChar);

                    $tokens[] = $this->addToken(TokenKind::Tag, $literal);
                }

                continue;
            }

            if ($this->isGreaterThan($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::GreaterThan, $currentChar);

                $this->skipWhitespace();

                $lookChar = $this->lookChar();

                if ($this->isLiteral($lookChar)) {
                    $currentChar = $this->readChar();
                    $literal     = $this->readRemark($currentChar);

                    $tokens[] = $this->addToken(TokenKind::Remark, $literal);
                }

                continue;
            }

            if ($this->isAt($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::At, $currentChar);
                continue;
            }

            if ($this->isLeftCurlyBracket($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::LeftCurlyBracket, $currentChar);
                continue;
            }

            if ($this->isRightCurlyBracket($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::RightCurlyBracket, $currentChar);
                continue;
            }

            if ($this->isLiteral($currentChar)) {
                $literal = $this->readString($currentChar);

                $tokens[] = $this->addToken(TokenKind::String, $literal);
                continue;
            }

            // When parsing file
            if ($this->isEndOfLine($currentChar)) {
                $tokens[] = $this->addToken(TokenKind::EOF, $currentChar);
                continue;
            }

            $this->stopLexer($currentChar);
        }

        // When parsing text
        $endOfFileToken = array_filter($tokens, function($token) {
            return $token->kind === TokenKind::EOF;
        });

        if (empty($endOfFileToken)) {
            $tokens[] = $this->addToken(TokenKind::EOF, "end_of_file");
        }

        return $tokens;
    }

    public function readChar(): string {
        $currentChar = mb_substr($this->content, $this->currentPosition, 1);

        if ($this->isNewline($currentChar)) {
            $this->currentColumn = 0;
            $this->currentLine++;
        }else{
            $this->currentColumn++;
        }

        $this->currentPosition++;

        return $currentChar;
    }

    public function lookChar(): string {
        $currentChar = mb_substr($this->content, $this->currentPosition, 1);

        return $currentChar;
    }

    public function lookNextChar(): string {
        $nextChar = mb_substr($this->content, $this->currentPosition + 1, 1);

        return $nextChar;
    }

    public function skipNewlineOrWhiteSpace(): void {
        $currentChar = $this->lookChar();

        while($this->isNewline($currentChar) || $this->isWhiteSpace($currentChar)) {
            $this->readChar();

            $currentChar = $this->lookChar();
        }
    }

    public function skipWhitespace(): void {
        $currentChar = $this->lookChar();

        while($this->isWhitespace($currentChar)) {
            $this->readChar();

            $currentChar = $this->lookChar();
        }
    }

    public function skipSingleComment(): void {
        $this->readChar(); // eat second slash

        while(!$this->isNewline($this->lookChar())) {
            $this->readChar();

            if ($this->currentPosition >= $this->contentLength) {
                break;
            }
        }
    }

    public function addToken(string $kind, string $char): Token {
        return new Token(
            kind  : $kind,
            value : $char,
            line  : $this->currentLine,
            column: $this->currentColumn,
        );
    }

    protected function stopLexer(string $char): never {
        throw new LexerException(
            $char,
            $this->currentLine,
            $this->currentColumn,
        );
    }

}
