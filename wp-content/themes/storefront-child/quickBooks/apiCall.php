<?php
    
    require_once(__DIR__ . '/vendor/autoload.php');
    
    use QuickBooksOnline\API\DataService\DataService;
    use QuickBooksOnline\API\Core\ServiceContext;
    use QuickBooksOnline\API\PlatformService\PlatformService;
    use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
    use QuickBooksOnline\API\Facades\Customer;
    
    session_start();
    
    function makeAPICall()
    {
        
        // Create SDK instance
        $config = include('config.php');
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => "development"
        ));
        
        /*
         * Retrieve the accessToken value from session variable
         */
        $accessToken = $_SESSION['sessionAccessToken'];
        
        /*
         * Update the OAuth2Token of the dataService object
         */
        
        // working api call

//        $dataService->updateOAuth2Token($accessToken);
//        $companyInfo = $dataService->getCompanyInfo();
//        $address = "QBO API call Successful!! Response Company name: " . $companyInfo->CompanyName . " Company Address: " . $companyInfo->CompanyAddr->Line1 . " " . $companyInfo->CompanyAddr->City . " " . $companyInfo->CompanyAddr->PostalCode;
//        print_r($companyInfo);
//        return $companyInfo;
        
        $dataService->updateOAuth2Token($accessToken);

// Iterate through all Customers, even if it takes multiple pages
        $i = 0;
        while (1) {
            $allCustomers = $dataService->FindAll('Customer', $i, 500);
            $error = $dataService->getLastError();
            if ($error) {
                echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
                echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                echo "The Response message is: " . $error->getResponseBody() . "\n";
                exit();
            }
            if (!$allCustomers || (0 == count($allCustomers))) {
                break;
            }
            foreach ($allCustomers as $oneCustomer) {
                echo "Customer[" . ($i++) . "]: {$oneCustomer->DisplayName}\n";
                echo "\t * Id: [{$oneCustomer->Id}]\n";
                echo "\t * Active: [{$oneCustomer->Active}]\n";
                echo "\n";
            }
        }
//        print_r($allCustomers);
//        return $allCustomers;
    
        $servername = "localhost";
        $username = "dematrix_playgrd";
        $password = "Y+2@9*aZ-PsY";
        $dbname = "dematrix_playgrd";
    
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
    
        $sql2 = "SELECT meta_value FROM wp_postmeta WHERE meta_key='accessTokenQB' AND post_id=1";
        $result = $conn->query($sql2);
        print_r($result);
        $row = $result->fetch_assoc();
        $accessTokenQB = $row["meta_value"];
        print_r($accessTokenQB);
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                print_r($result);
                echo "accessTokenQB: " . $row["meta_value"] . "<br>";
            }
        } else {
            echo "0 results";
        }
    
        $conn->close();
    
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://sandbox-quickbooks.api.intuit.com/v3/company/123146365467839/companyinfo/123146365467839",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer ".$accessTokenQB,
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
        
    }
    
    $result = makeAPICall();

?>
