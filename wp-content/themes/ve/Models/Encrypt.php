<?php
class Encryption_Model
{
    var $scramble1;      // 1st string of ASCII characters
    var $scramble2;      // 2nd string of ASCII characters
    var $errors;         // array of error messages
    var $adj;            // 1st adjustment value (optional)
    var $mod;            // 2nd adjustment value (optional)

    function __construct($emailSafeChars = false)
    {
        // Each of these two strings must contain the same characters, but in a different order.
        // Use only printable characters from the ASCII table.
        // Do not use single quote, double quote or backslash as these have special meanings in PHP.
        // Each character can only appear once in each string.
        if(!$emailSafeChars)
        {
            //$this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_abcdefghijklmnopqrstuvwxyz{|}~';
            //$this->scramble2 = 'f^jAE]okIOzU[2&q1{3h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';

            $this->scramble1 = '012/3456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_.abcdefghijklmnopqrstuvwxyz';
            $this->scramble2 = 'fjAEokI_OzU2q13h5w794p6s8Bg.PdFVmDTcSZerlGKuCyJxHiQLtRM/aNvWYnb0X';
        }
        else
        {
            $this->scramble1 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';
            $this->scramble2 = 'fjAEokIOzU2q13h5w794p6s8BgPdFVmDTcSZerlGKuCyJxHiQLtRMaNvWYnb0X-_';
        }

        if (strlen($this->scramble1) <> strlen($this->scramble2)) {
        trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
        } // if

        $this->adj = 1.75;    // this value is added to the rolling fudgefactors
        $this->mod = 3;       // if divisible by this the adjustment is made negative

    } // constructor

    function encrypt ($key, $source, $sourcelen=0)
    {
        // The first step is to convert the key into an array of numbers which I call $fudgefactor.
        // This array is used to 'fudge' or 'adjust' the index number obtained from the first character string before it is used on the second character string.
        // The contents of the _convertkey function can be viewed here.

        $this->errors = array();

        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        //This next piece of code checks that a source string has actually been supplied.
        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for encryption';
            return;
        }

        //Next we pad the input string with spaces up the specified length.
        while (strlen($source) < $sourcelen) {
            $source .= ' ';
        } // while

        //Here we are setting up a for loop to process each character from $source.
        $target  = NULL;
        $factor2 = 0;
        for ($i = 0; $i < strlen($source); $i++)
        {
            // Here we extract the next character from $source and identify its position in $scramble1.
            // Note that we cannot continue processing if the character cannot be found.
            $char1 = substr($source, $i, 1);
            $num1 = strpos($this->scramble1, $char1);
            if ($num1 === false) {
                $this->errors[] = "Source string contains an invalid character ($char1)";
                return;
            } // if

            // Next we obtain the adjustment value from the $fudgefactor array and accumulate it in $factor1 along with the previous contents of $factor2.
            // The contents of the _applyFudgeFactor function can be viewed here.
            $adj = $this->_applyFudgeFactor($fudgefactor);
            $factor1 = $factor2 + $adj;                  // accumulate in $factor1

            // Here we add $factor1 to the offset from the $scramble1 string ($num1) to give us the offset into the $scramble2 string ($num2).
            // Note that factor may contain decimal digits, so it has to be rounded in order to supply a whole number.
            $num2 = round($factor1) + $num1;        // generate offset for $scramble2

            // The value at this point may be a negative number or even a large positive number,
            // so we have to check that it can actually point to a character in the $scramble2 string.
            // The contents of the _checkRange function can be viewed here.
            $num2 = $this->_checkRange($num2);      // check range

            // As an added complication to confuse potential hackers we are also accumulating the value of $num2 and $factor1 in $factor2.
            $factor2 = $factor1 + $num2;               // accumulate in $factor

            // Here we extract a character from $scramble2 using the value in $num2 and append it to the output string.
            $char2 = substr($this->scramble2, $num2, 1);
            $target .= $char2;

            // Finally we close the for loop and return the encrypted string.
        }
        return $target;
    } // encrypt

    function _convertKey ($key)
    {
        if (empty($key)) {
            $this->errors[] = 'No value has been supplied for the encryption key';
            return;
        }

        // The first entry in the array is the length of the $key string.
        $array[] = strlen($key);

        // Next we set up a for loop to examine every character in the $key string.
        $tot = 0;
        for ($i = 0; $i < strlen($key); $i++)
        {
            // Here we extract the next character from $key and identify its position in $scramble1.
            // Note that we cannot continue processing if the character cannot be found.
            $char = substr($key, $i, 1);
            $num = strpos($this->scramble1, $char);
            if ($num === false) {
                $this->errors[] = "Key contains an invalid character ($char)";
                return;
            }

            //He we append the number to the output array and accumulate the total for later.
            $array[] = $num;
            $tot = $tot + $num;

            //At the end of the for loop we add the accumulated total to the end of the array and return the array to the calling process.
        }
        $array[] = $tot;
        return $array;
    } // _convertKey

    function _applyFudgeFactor (&$fudgefactor)
    {
        // Here we extract the first entry in the array and remove it from the array.
        $fudge = array_shift($fudgefactor);

        //Next we add in the optional $adj value and put the result back into the end of the array.
        $fudge = $fudge + $this->adj;
        $fudgefactor[] = $fudge;

        // If a $modulus value has been supplied we use it and possibly reverse the sign on the output value.
        if (!empty($this->mod))           // if modifier has been supplied
            if ($fudge % $this->mod == 0)  // if it is divisible by modifier
                $fudge = $fudge * -1;         // reverse then sign

        // There is no more 'fudging' left to do, so we can return the value to the calling process.
        return $fudge;
    } // _applyFudgeFactor


    function _checkRange ($num)
    {
        // First we must round up to the nearest whole number.
        $num = round($num);

        // We use the length of the scramble string as the limit.
        $limit = strlen($this->scramble1);

        // If the value is too high we must reduce it.
        while ($num >= $limit) {
            $num = $num - $limit;
        }

        // If the value is too low we must increase it.
        while ($num < 0) {
            $num = $num + $limit;
        }

        // We can now return a valid pointer back to the calling process.
        return $num;
    } // _checkRange

    function decrypt ($key, $source)
    {
        // The first step is to convert the key into an array of numbers which I call $fudgefactor. The contents of the _convertkey function can be viewed here.
        $this->errors = array();
        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        // This next piece of code checks that a source string has actually been supplied.
        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for decryption';
            return;
        }

        // Here we are setting up a for loop to process each character from $source.
        $target  = NULL;
        $factor2 = 0;
        for ($i = 0; $i < strlen($source); $i++)
        {
            // Here we extract the next character from $source and identify its position in $scramble2.
            // Note that we cannot continue processing if the character cannot be found.
            $char2 = substr($source, $i, 1);
            $num2  = strpos($this->scramble2, $char2);
            if ($num2 === false) {
                $this->errors[] = "Source string contains an invalid character ($char2)";
                return;
            } // if

            // Next we obtain the adjustment value from the $fudgefactor array and accumulate it in $factor1 along with the previous contents of $factor2.
            // The contents of the _applyFudgeFactor function can be viewed here.

            $adj = $this->_applyFudgeFactor($fudgefactor);
            $factor1 = $factor2 + $adj;

            // Here we add $factor1 to the offset from the $scramble2 string ($num2) to give us the offset into the $scramble1 string ($num1).
            // Note that factor may contain decimal digits, so it has to be rounded in order to supply a whole number.
            $num1 = $num2 - round($factor1);        // generate offset for $scramble1

            // The value at this point may be a negative number or even a large positive number,
            // so we have to check that it can actually point to a character in the $scramble1 string. The contents of the _checkRange function can be viewed here.
            $num1 = $this->_checkRange($num1);      // check range

            //As an added complication to confuse potential hackers we are also accumulating the value of $num2 and $factor1 in $factor2.
            $factor2 = $factor1 + $num2;               // accumulate in $factor2

            //Here we extract a character from $scramble1 using the value in $num1 and append it to the output string.
            $char1 = substr($this->scramble1, $num1, 1);
            $target .= $char1;

            //Finally we close the for loop, return the decrypted string, and close the class.
        }
        return rtrim($target);
    } // decrypt
} // end encryption_class

?>