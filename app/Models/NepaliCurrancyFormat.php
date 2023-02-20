<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NepaliCurrancyFormat extends Model
{
    use HasFactory;
    function moneyFormatIndia($num)
    {
        $val = explode('.', $num);
        $num = $val[0];
        $decimal = @$val[1];


        $explrestunits = "";
        if (strlen($num) > 3) {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $num;
        }
        if (@$decimal != null) {
            if (strlen($decimal) < 2) {
                $decimal = $decimal . "0";
            }
            return $thecash . "." . $decimal; // writes the final format where $currency is the currency symbol.
        } else {
            return $thecash . ".00"; // writes the final format where $currency is the currency symbol.
        }
    }
}
