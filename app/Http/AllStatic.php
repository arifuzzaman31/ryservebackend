<?php
namespace App\Http;

class AllStatic
{

    public static $active   = 1;
    public static $inactive = 0;

    // payment status
    public static $paid     = 1;
    public static $not_paid = 0;
    public static $failed = 2;
    public static $cancel = 3;

    // delivery status
    public static $pending     = 0;
    public static $processing  = 1;
    public static $on_delivery = 2;
    public static $delivered   = 3;

    // payment method  status
    //0 for COD/1 for MPAY/ 2 for POS/ 3 for CCRD/4 for BOD if not set default COD

    public static $cod    = 0;
    public static $mpay   = 1;
    public static $pos    = 2;
    public static $ccrd   = 3;
    public static $bod    = 4;

}
