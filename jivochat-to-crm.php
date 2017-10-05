<?php

/**
 *
 * @link              http://egany.com
 * @since             1.0.0
 * @package           Jivochat to crm
 *
 * @wordpress-plugin
 * Plugin Name:       Jivochat to crm
 * Plugin URI:        http://egany.com
 * Description:       Jivochat to crm
 * Version:           1.0.0
 * Author:            EGANY
 * Author URI:        http://egany.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gumroad-user-support
 */

/**
* ping back url from jivochat
*/
add_action( 'init', 'jivochat_to_crm_init' );
function jivochat_to_crm_init() {
    add_rewrite_rule( '^jivochat-to-crm-endpoint$', 'index.php?jivochat-to-crm-endpoint=true', 'top' );
}
//WordPress has a whitelist of variables it allows, so we must put it on that list
add_action( 'query_vars', 'jivochat_query_vars' );
function jivochat_query_vars( $query_vars )
{
    $query_vars[] = 'jivochat-to-crm-endpoint';
    return $query_vars;
}

add_action( 'parse_request', 'jivochat_parse_request' );
function jivochat_parse_request( &$wp ) 
{

    if ( array_key_exists( 'jivochat-to-crm-endpoint', $wp->query_vars ) ) {
        status_header(200);
        // $path = ABSPATH ;
        // $path = rtrim(ABSPATH,"/");
        // $path = $path."/jivochat.txt";
        // if(!file_exists($path))
        // {
        //     file_put_contents($path, "");
        // }
        // $c = file_get_contents($path);
        $data_json = file_get_contents('php://input');
        $data = json_decode($data_json, true);
        $visitor = $data['visitor'];
        // file_put_contents( $path, $c. '\r\n'. "event:". $data['event_name']." visitor:".json_encode($visitor));
        //send to zoho crm
        if($data['event_name'] == 'chat_accepted')
        {
            if($visitor)
            {
                $name = isset($visitor['name']) ? $visitor['name'] : '';
                $phone = isset($visitor['phone']) ? $visitor['phone'] : '';
                $email = isset($visitor['email']) ? $visitor['email'] : '';
                if($name && $phone && $email)
                {
                    $token="e683ab93b394e4b721e55ae1cd3eb3ce";
                    $param= "authtoken=".$token."&scope=crmapi&newFormat=1&scope=crmapi";
                    $param.="&xmlData=".'<Leads>
                    <row no="1">
                    <FL val="Lead Source">Jivochat</FL>
                    <FL val="Company">Unkown</FL>
                    <FL val="First Name">'.$name.'</FL>
                    <FL val="Last Name">Unkown</FL>
                    <FL val="Email">'.$email.'</FL>
                    <FL val="Title"></FL>
                    <FL val="Phone">'.$phone.'</FL>
                    <FL val="Home Phone">Unkown</FL>
                    <FL val="Other Phone">Unkown</FL>
                    <FL val="Fax">Unkown</FL>
                    <FL val="Mobile">Unkown</FL>
                    </row>
                    </Leads>';
                    $url = 'https://crm.zoho.com/crm/private/xml/Leads/insertRecords';
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
                    $result = curl_exec($ch);
                    header("Content-type: application/xml");
                    echo $result;
                    curl_close($ch);

                    //$c = file_get_contents($path);
                    //file_put_contents( $path, $c. " result: ".$result);
                }

            }
        }
        exit();
    }
}



