<?php
/*
 * Первые четыре символа - это ASN1-префикс.  (c) форум крипто-про
 * https://www.cryptopro.ru/forum2/default.aspx?g=posts&t=12315
 */
$input = file_get_contents($argv[1]);
mb_internal_encoding('UTF-8');
echo mb_substr( $input, 4);

/*
 * Ранее обрезали до первой алфавитной буквы, оставим на всякий
 */
//
//$chars = '0123456789~!@#$%^&*()-_=+:;,."\'\\/|{}[]<> ';
//
//$length = strlen($input);
//$pos = 1;
//
//while($pos < $length) {
//	$char = $input{$pos};
//	if (strpos($chars, $char) !== false) {
//		break;
//	}
//	$ord = ord($char);
//	if ($ord >= ord('A') && $ord <= ord('Z')) {
//		break;
//	}
//	if ($ord >= ord('a') && $ord <= ord('z')) {
//		break;
//	}
//	$pos++;
//}
//
//$output = substr($input, $pos);
//
//echo $output;
?>

