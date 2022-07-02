<?php

// define log lovely constraints
const LOG_DEBUG = 1;
const LOG_INFO = 2;
const LOG_NOTICE = 3;
const LOG_WARNING = 4;
const LOG_CRITICAL = 5;

/**
 * Creates a log entry
 * @param string $caller The caller for the log.
 * @param string $message The message of the log.
 * @param int $level The log level. (Use the defined constants)
 * @param array|null $data A data array which should be added to the log.
 * @return void
 */
function logData(string $caller, string $message, int $level = LOG_LVL_INFO, ?array $data = null): void
{
    $today = date("Y-m-d");
    $now = date("Y-m-d H:i:s");
    if (!is_dir(LOG_DIR)) {
        mkdir(LOG_DIR, 0777, true);
    }

    switch ($level) {
        default:
        case LOG_LVL_INFO:
            $lvl = "INFO";
            break;
        case LOG_LVL_NOTICE:
            $lvl = "NOTICE";
            break;
        case LOG_LVL_WARNING:
            $lvl = "WARNING";
            break;
        case LOG_LVL_CRITICAL:
            $lvl = "CRITICAL";
            break;
        case LOG_LVL_EMERGENCY:
            $lvl = "EMERGENCY";
            break;
        case LOG_LVL_DEBUG:
            $lvl = "DEBUG";
            break;
    }

    $logFile = LOG_DIR . "/log-" . $today . ".log";

    $logData = "[" . $now . "-" . $lvl . "] [" . $caller . "]" . $message . "\n";

    if ($data) {
        $dataString = print_r($data, true) . "\n";
        $logData .= $dataString;
    }

    file_put_contents($logFile, $logData, FILE_APPEND);
}