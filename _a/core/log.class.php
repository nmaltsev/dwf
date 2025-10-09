<?php

namespace Core\Utils;

if (!defined('STDOUT')) define('STDOUT', fopen('php://output', 'w'));
if (!defined('STDERR')) define('STDERR', fopen('php://stderr', 'w'));

class Log
{
    private $prefix;

    private function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * Factory method to create a Log instance
     */
    public static function create(string $prefix=''): self
    {
        return new self($prefix);
    }

    /**
     * Write an info message to stdout
     */
    public function info(string $message): void
    {
        $this->writeToStream(STDOUT, "INFO", $message);
    }

    /**
     * Write an error message to stderr
     */
    public function error(string $message): void
    {
        $this->writeToStream(STDERR, "ERROR", $message);
    }

    /**
     * Pretty print arrays/objects to stdout
     */
    public function debug(mixed $data): void
    {
        $formatted = print_r($data, true);
        $this->writeToStream(STDOUT, "DEBUG", $formatted);
    }

    /**
     * Internal method to handle stream writing
     */
    private function writeToStream($stream, string $level, string $message): void
    {
        $timestamp = date('Y-m-d H:i:s');
        fwrite($stream, sprintf("[%s] [%s] %s: %s\n", $timestamp, $level, $this->prefix, $message));
    }
}
