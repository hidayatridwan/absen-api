<?php

namespace RidwanHidayat\Absen\API\Helper;

class Helper
{

    public static function parseToPost(): void
    {
        $stream = file_get_contents('php://input');
        $raw = json_decode($stream, true);

        if ($raw !== null) {
            foreach ($raw as $key => $row) {
                $_POST[$key] = $row;
            }
        }
    }
}