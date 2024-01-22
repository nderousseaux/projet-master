<?php

$env = parse_ini_file('.env');
$smtpId = $env["SMTP_ID"];
$smtpPw = $env["SMTP_PW"];

echo "$smtpId $smtpPw";