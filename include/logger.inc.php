<?php
// module for logging all server internal errors and information to a central file

// define log lovely constraints
const LOG_DEBUG = 1;
const LOG_INFO = 2;
const LOG_NOTICE = 3;
const LOG_WARNING = 4;
const LOG_CRITICAL = 5;

/**
 * Creates a log entry in './assets/logs/'
 * @param string $caller The caller for the log.
 * @param string $message The message of the log.
 * @param int $level The log level. (Use the defined constants)
 * @param array|null $data A data array which should be added to the log.
 * @return void
 */
function logData(string $caller, string $message, int $level = LOG_INFO, ?array $data = null): void
{
    // only log if min log level is less or equal the given level
    if (MIN_LOG_LEVEL <= $level){
        // gather date information
        $today = date("Y-m-d");
        $now = date("Y-m-d H:i:s");

        // create directory if not exist
        if (!is_dir(LOG_DIR)) {
            mkdir(LOG_DIR, 0777, true);
        }

        switch ($level) {
            default:
            case LOG_INFO:
                $lvl = "INFO";
                break;
            case LOG_DEBUG:
                $lvl = "DEBUG";
                break;
            case LOG_NOTICE:
                $lvl = "NOTICE";
                break;
            case LOG_WARNING:
                $lvl = "WARNING";
                break;
            case LOG_CRITICAL:
                $lvl = "CRITICAL";
                break;
        }

        $logFile = LOG_DIR . "/log-" . $today . ".log";

        $logData = "[" . $now . "-" . $lvl . "] [" . $caller . "]" . $message . "\n";

        if ($data) {
            $dataString = print_r($data, true) . "\n";
            $logData .= $dataString;
        }

        // write entry to log file
        file_put_contents($logFile, $logData, FILE_APPEND);
    }
}