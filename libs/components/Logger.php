<?php

namespace libs\components;

/**
 * Class Logger
 *
 * @author Yuriy Stos
 */
class Logger
{
    /**
     * @var string - Path to log
     */
    private $logPath;

    /**
     * Logger constructor.
     * @param string $path - Path to log
     */
    public function __construct($path)
    {
        $this->logPath = $path;
    }

    /**
     * Insert message to log
     * TODO: add checking of permission
     *
     * @param $message - Message
     */
    public function insert($message)
    {
        file_put_contents($this->logPath, date('Y-m-d H:i:s') . ': ' . $message . "\n", FILE_APPEND);
    }
}
