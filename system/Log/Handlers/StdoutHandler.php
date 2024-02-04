<?php

namespace CodeIgniter\Log\Handlers;

use DateTime;
use Exception;

/**
 * Log error messages to STDOUT
 *
 */
class StdoutHandler extends BaseHandler
{
    /**
     * Constructor
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * Handles logging the message.
     * If the handler returns false, then execution of handlers
     * will stop. Any handlers that have not run, yet, will not
     * be run.
     *
     * @param string $level
     * @param string $message
     *
     * @throws Exception
     */
    public function handle($level, $message): bool
    {
        $msg = '';

        // Instantiating DateTime with microseconds appended to initial date is needed for proper support of this format
        if (strpos($this->dateFormat, 'u') !== false) {
            $microtimeFull  = microtime(true);
            $microtimeShort = sprintf('%06d', ($microtimeFull - floor($microtimeFull)) * 1_000_000);
            $date           = new DateTime(date('Y-m-d H:i:s.' . $microtimeShort, (int) $microtimeFull));
            $date           = $date->format($this->dateFormat);
        } else {
            $date = date($this->dateFormat);
        }

        $msg .= strtoupper($level) . ' - ' . $date . ' --> ' . $message . "\n";

        return false != file_put_contents('php://stdout', $msg);
    }
}