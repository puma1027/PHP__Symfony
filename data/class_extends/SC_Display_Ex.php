<?php

require_once CLASS_REALDIR . 'SC_Display.php';

class SC_Display_Ex extends SC_Display
{
    public static function detectDevice($reset = FALSE) {
        return DEVICE_TYPE_SMARTPHONE;
    }
}
