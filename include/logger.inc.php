<?php
// module for logging all server internal errors and information to a central file

// define log lovely constraints
const DEBUG_LOG = 1;
const INFO_LOG = 2;
const NOTICE_LOG = 3;
const WARNING_LOG = 4;
const CRITICAL_LOG = 5;

/**
 * Creates a log entry in './assets/logs/'
 * @param string $caller The caller for the log.
 * @param string $message The message of the log.
 * @param int $level The log level. (Use the defined constants)
 * @param array|null $data A data array which should be added to the log.
 * @return void
 */
function logData(string $caller, string $message, int $level = INFO_LOG, ?array $data = null): void
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
            case INFO_LOG:
                $lvl = "INFO";
                break;
            case DEBUG_LOG:
                $lvl = "DEBUG";
                break;
            case NOTICE_LOG:
                $lvl = "NOTICE";
                break;
            case WARNING_LOG:
                $lvl = "WARNING";
                break;
            case CRITICAL_LOG:
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