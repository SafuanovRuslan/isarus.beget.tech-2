<?php
echo preg_replace(['/\r\n/', '/\r/', '/\n/'], '<br>', $log);