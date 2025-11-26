<?php
    $path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
    include($path . 'wp-load.php');
    
    require_once(__DIR__ . '/vendor/autoload.php');
    
    use QuickBooksOnline\API\DataService\DataService;
    
    $config = include('config.php');
    
    session_start();
    
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $config['client_id'],
        'ClientSecret' => $config['client_secret'],
        'RedirectURI' => $config['oauth_redirect_uri'],
        'scope' => $config['oauth_scope'],
        'baseUrl' => "production"
    ));
    
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
    
    // Store the url in PHP Session Object;
    $_SESSION['authUrl'] = $authUrl;
    
    $connection = false;
    
    //set the access token using the auth object
    if (isset($_SESSION['sessionAccessToken'])) {
        
        $accessToken = $_SESSION['sessionAccessToken'];
        $sessionAccessToken = $_SESSION['sessionAccessToken'];
//        echo '<pre>';
//        print_r($accessToken);
//        echo '</pre>';
        //update_post_meta(1, 'accessTokenQB', $accessToken->getAccessToken());
        $accessTokenJson = array('token_type' => 'bearer',
            'access_token' => $accessToken->getAccessToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
            'expires_in' => $accessToken->getAccessTokenExpiresAt()
        );
        $dataService->updateOAuth2Token($accessToken);
        $oauthLoginHelper = $dataService->getOAuth2LoginHelper();
        $CompanyInfo = $dataService->getCompanyInfo();

//        echo '</br>Access Token:<pre>';
//        print_r($accessTokenJson);
//        echo '</pre></br>';
        
        update_post_meta(1, 'accessTokenQB', $accessToken->getAccessToken());
        update_post_meta(1, 'sessionAccessToken', array('session' => $sessionAccessToken));
        update_post_meta(1, 'refreshTokenKey', $accessToken->getRefreshToken());
        
        $accessTokenQB = get_post_meta(1, 'accessTokenQB', true);
        $sessionAccessToken = get_post_meta(1, 'sessionAccessToken', true);
        $connection = true;
        
        // print_r($sessionAccessToken);
    } else {
        echo '<h4>No sessionAccessToken</h4>';
        $connection = false;
    }

?>

<?php
    //    $servername = "localhost";
    //    $username = "matrixli_fetsw";
    //    $password = "ca{PpQw()A2K";
    //    $dbname = "matrixli_fetsw";
    //
    //    // Create connection to wordpress db to insert a meta with accessToken
    //
    //    $conn = new mysqli($servername, $username, $password, $dbname);
    //    // Check connection
    //    if ($conn->connect_error) {
    //        die("Connection failed: " . $conn->connect_error);
    //    }
    //
    //    // If session with access token exist then save in meta
    //    if (isset($_SESSION['sessionAccessToken'])) {
    //
    //        $sessionAccessToken = $_SESSION['sessionAccessToken'];
    //
    //
    //        $sql = "UPDATE wp_postmeta SET meta_value='" . $accessToken->getAccessToken() . "' WHERE post_id=1 AND meta_key='accessTokenQB'";
    //       // $sql2 = "UPDATE wp_postmeta SET meta_value='" . $sessionAccessToken . "' WHERE post_id=1 AND meta_key='sessionAccessToken'";
    //
    //        //    $sql = "UPDATE wp_postmeta SET  (post_id, meta_key, meta_value)
    //        //VALUES ('1', 'accessTokenQB', '". $accessToken->getAccessToken(). "' )";
    //
    //        if ($conn->query($sql) === TRUE) {
    //            echo "Record updated successfully" . "<br>";
    //        } else {
    //            echo "Error updating record: " . $conn->error . "<br>";
    //        }
    //
    ////        if ($conn->query($sql2) === TRUE) {
    ////            echo "Record updated successfully" . "<br>";
    ////        } else {
    ////            echo "Error updating record: " . $conn->error . "<br>";
    ////        }
    //
    //
    //        update_post_meta(1,'accessTokenQB',$accessToken->getAccessToken());
    //        update_post_meta(1,'sessionAccessToken', array( 'session'=>$sessionAccessToken) );
    //    }
    //
    //    // Get from database accessToken saved from session to use in curl
    //    $sql3 = "SELECT meta_value FROM wp_postmeta WHERE meta_key='accessTokenQB' AND post_id=1";
    //    $result = $conn->query($sql3);
    //    print_r($result);
    //    $row = $result->fetch_assoc();
    //    $accessTokenQB = $row["meta_value"];
    //    print_r($accessTokenQB);
    //    if ($result->num_rows > 0) {
    //        // output data of each row
    //        while ($row = $result->fetch_assoc()) {
    //            print_r($result);
    //            echo "accessTokenQB: " . $row["meta_value"] . "<br>";
    //        }
    //    } else {
    //        echo "0 results";
    //    }
    //
    //    $conn->close();
    //
    //    $sessionAccessToken = get_post_meta(1,'sessionAccessToken',true);
    //    echo 'sessionAccessToken : <br>';
    //    print_r($sessionAccessToken);
?>

<script>

    var url = '<?php echo $authUrl; ?>';

    var OAuthCode = function (url) {

        this.loginPopup = function (parameter) {
            this.loginPopupUri(parameter);
        }

        this.loginPopupUri = function (parameter) {

            // Launch Popup
            var parameters = "location=1,width=800,height=650";
            parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;

            var win = window.open(url, 'connectPopup', parameters);
            var pollOAuth = window.setInterval(function () {
                try {

                    if (win.document.URL.indexOf("code") != -1) {
                        window.clearInterval(pollOAuth);
                        win.close();
                        location.reload();
                    }
                } catch (e) {
                    console.log(e)
                }
            }, 100);
        }
    }


    var apiCall = function () {
        this.getCompanyInfo = function () {
            /*
            AJAX Request to retrieve getCompanyInfo
             */
            jQuery.ajax({
                type: "GET",
                url: "apiCall.php",
            }).done(function (msg) {
                jQuery('#apiCall').html(msg);
            });
        };

        this.refreshToken = function () {
            jQuery.ajax({
                type: "POST",
                url: "/wp-content/themes/storefront-child/quickBooks/refreshToken.php",
            }).done(function (msg) {
                console.log(msg);
                location.reload();
            });
        }
    };

    var oauth = new OAuthCode(url);
    var apiCall = new apiCall();
</script>

<div class="container">
    
    <p>If there is no access token or the access token is invalid, click the
        <b>Connect to QuickBooks</b> button below.
    
    <!--    <pre id="accessToken">-->
    <!--        <style="background-color:#efefef;overflow-x:scroll">--><?php
        //            $displayString = isset($accessTokenJson) ? $accessTokenJson : "No Access Token Generated Yet";
        //            echo json_encode($displayString, JSON_PRETTY_PRINT); ?>
    <!--    </pre>-->
    <?php
        // print_r( $_SESSION['sessionAccessToken'] );
        // <button class="btn btn-success" onclick="apiCall.refreshToken()">Refresh Connection</button>
        if (isset($_SESSION['sessionAccessToken'])) {
            echo '<br><span>QuickBooks Connected - Access Token Generated</span> <button class="btn btn-success" onclick="oauth.loginPopup()"> Token Generate</button> ';
        } else {
            echo '<span>No Access Token Generated Yet</span> <button class="btn btn-success" onclick="oauth.loginPopup()"> Token Generate</button>';
        }
        //        $displayString = isset($accessTokenJson) ? $accessTokenJson : '<h3> <button class="btn btn-success" onclick="oauth.loginPopup()"> Token Generate</button> No Access Token Generated Yet</h3>';
        //        echo '<h3>QuickBooks Connected - Access Token Generated</h3> <button class="btn btn-success" onclick="apiCall.refreshToken()">Refresh Connection</button>'
    ?>
    </p>
</div>

