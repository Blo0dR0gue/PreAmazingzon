<?php

const LOG_LVL_INFO = 0;
const LOG_LVL_NOTICE = 1;
const LOG_LVL_WARNING = 2;
const LOG_LVL_CRITICAL = 3;
const LOG_LVL_EMERGENCY = 4;
const LOG_LVL_DEBUG = 5;

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