<?php

echo 'asdasd';

//if( $_POST ){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "quickbooks.api.intuit.com/v3/company/123145612991942/query?minorversion={{minorversion}}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "Select * from Account STARTPOSITION 1 MAXRESULTS 5\n",
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Authorization: Bearer eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..pWuPdfkaTcgaZWOPkGUXug.5ZT8ZmnvyLdXi6qmeWgNv0HyTr2tGF3_5X_qMZwcqAqJJMVr_x5Nqf6mNcw-gkXqVzhlJV776fA4brKvAQWlVxBqmeqL_WGAJd0qx9S262ubN6qPV5aYpOwyCFPgpDpbh7Z4omnRCh1URHwi7xrtzXdi3AucRygLcpclVYmHoFQZ_LOSYyOCNs1E4bXF74uXdyoC_oIGYHJ2P0Yia2fmbR_I2wk-cKJSkIf2IFzFEYsZyXRq7EIZOdwmSmN6BMAMaFbO_SjqXxj1VTsyI3ai-C5TTbEI8JFK5phSDq-Mj3S71SHN4iXCyIEM3MmVFuEuVEkDcU__DFpQQZ0qLnexJrICs3Uo_QySIBKLwnHhcXAEeMIaZz_gIxkcMqcceU_4DCfOGnK7dpGYRR0e6-T-_jey1hV3eH92vqyyiAYLsVyABEbmdOrIGjEdxQS6giiPJ-zpPiH3l8FDKXAG3O2KVityFsGxf2gfxgv9qbPePAjigu9O-_h48TOhe8zq3tKfpOYd9qTb6UpmrRMUfk7Wljikc3-92qI-OmV5gZWWYukfoTTlQI45YhRqFcohXO8u2DYjtp0SJpJdvrmJ-OGUNBMznMZHC6wl7zN1ONTYS5giqynNKNNPxJa4UAdDsdI7EZqTGwNI7pngyw3azpWqhI-TtVPnau7I713GhnEWmI7I_r90D6-YK2xz77-uQ-NH.VnBzj2aOMj9qKXHvmpOsKA",
            "Content-Type: application/json",
            "User-Agent: {{UserAgent}}",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
        echo $response;
        }


//}

?>