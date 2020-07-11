<?php
/**
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("encdec_paytm.php");

/* initialize an array */
$paytmParams = array();

/* body parameters */
$paytmParams["body"] = array(

    /* for custom checkout value is 'Payment' and for intelligent router is 'UNI_PAY' */
    "requestType" => "Payment",

    /* Find your MID in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
    "mid" => "YOUR_MID_HERE",

    /* Find your Website Name in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys */
    "websiteName" => "YOUR_WEBSITE_NAME",

    /* Enter your unique order id */
    "orderId" => "YOUR_ORDER_ID",

    /* on completion of transaction, we will send you the response on this URL */
    "callbackUrl" => "YOUR_CALLBACK_URL",

    /* Order Transaction Amount here */
    "txnAmount" => array(

        /* Transaction Amount Value */
        "value" => "TRANSACTION_AMOUNT_VALUE",

        /* Transaction Amount Currency */
        "currency" => "TRANSACTION_AMOUNT_CURRENCY",
    ),

    /* Customer Infomation here */
    "userInfo" => array(

        /* unique id that belongs to your customer */
        "custId" => "CUSTOMER_ID",
    ),
);

/**
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = getChecksumFromString(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "YOUR_KEY_HERE");

/* head parameters */
$paytmParams["head"] = array(

    /* put generated checksum value here */
    "signature"	=> $checksum
);

/* prepare JSON string for request */
$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=YOUR_ORDER_ID";

/* for Production */
// $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=YOUR_ORDER_ID";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);