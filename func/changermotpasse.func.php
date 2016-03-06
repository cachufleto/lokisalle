<?php

/**
 * @return string
 */
function usersChangerMotPasse(){

    global $minLen;

    $_trad = setTrad();
    include PARAM . 'changermotpasse.param.php';
    $message = '';
    $sql_Where = '';

    postCheck($_formulaire);

    foreach ($_formulaire as $key => $info){

        $label = $_trad['champ'][$key];
        $valeur = (isset($info['valide']))? $info['valide'] : NULL;

        if ('valide' != $key)
            if (isset($info['maxlength']) && (strlen($valeur) < $minLen  || strlen($valeur) > $info['maxlength']))
            {

                $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                    ': doit avoir un nombre de caracter compris entre ' . $minLen .
                    ' et ' . $info['maxlength'] . ' </p></div>';

            } else {

                switch($key){
                    case 'mdp':
                        $crypte = $key;
                        break;

                    case 'pseudo':
                        $verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $valeur );
                        if (!$verif_caractere  && !empty($valeur))
                        {
                            $message.= '<div class="bg-danger message"> <p> Erreur sur le ' .$label.
                                ', Caractere acceptés: A à Z et 0 à 9 </p></div>';
                            // un message sans ecresser les messages existant avant. On place dans $msg des chaines de caracteres
                        }
                        break;
                }
                // Construction de la requettes
                if ($key != 'mdp') $sql_Where .= ((!empty($sql_Where))? " AND " : "") . $key.'="' . $valeur . '" ';
            }
    }

    if (empty($message)) // si la variable $msg est vide alors il n'y a pas d'erreurr !
    {
        if ($membre = usersSelectChangerMotPasse($sql_Where))
        {
            if (isset($crypte) && hashDeCrypt($_formulaire[$crypte])){
                $_formulaire[$crypte]['sql'] = $membre[$crypte];
                if (hashDeCrypt($_formulaire[$crypte])){
                    // overture d'une session Membre
                    ouvrirSession($membre);
                    $message = 'OK';
                }
            }
        } else {
            $message .= '<div class="bg-danger message"> <p>Une erreur est survenue ! </p>';
        }
    }

    return $message;
}

/**
 * @param $sql_Where
 * @return bool
 */
function usersSelectChangerMotPasse($sql_Where)
{
    $sql = "SELECT id_membre FROM membres WHERE $sql_Where;";
    return executeRequete($sql);
}
