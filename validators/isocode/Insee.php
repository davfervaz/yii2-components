<?php

namespace dlds\components\validators\isocode;

class Insee extends ISOValidator {

    const PATTERN = '/^                                           # début de chaîne
            (?<sexe>[1278])                                             # 1 et 7 pour les hommes ou 2 et 8 pour les femmes
            (?<annee>[0-9]{2})                                          # année de naissance
            (?<mois>0[1-9]|1[0-2]|20)                                   # mois de naissance (si >= 20, c\'est qu\'on ne connaissait pas le mois de naissance de la personne
                    (?<departement>[02][1-9]|2[AB]|[1345678][0-9]|9[012345789]) # le département : 01 à 19, 2A ou 2B, 21 à 95, 99 (attention, cas particulier hors métro traité hors expreg)
                    (?<numcommune>[0-9]{3})                                     # numéro d\'ordre de la commune (attention car particuler pour hors métro  traité hors expression régulière)
                    (?<numacte>00[1-9]|0[1-9][0-9]|[1-9][0-9]{2})               # numéro d\'ordre d\'acte de naissance dans le mois et la commune ou pays
                    (?<clef>0[1-9]|[1-8][1-9]|9[1-7])?                          # numéro de contrôle (facultatif)
                    $                                                           # fin de chaîne
                    /x';

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        if (!preg_match(self::PATTERN, $value, $match))
        {
            return false;
        }

        $return = array(
            'sexe' => $match['sexe'], //7,8 => homme et femme ayant un num de sécu temporaire
            'annee' => $match['annee'], //année de naissance + ou - un siècle uhuh
            'mois' => $match['mois'], //20 = inconnu
            'departement' => $match['departement'], //99 = étranger
            'numcommune' => $match['numcommune'], //990 = inconnu
            'numacte' => $match['numacte'], //001 à 999
            'clef' => isset($match['clef']) ? $match['clef'] : null, //00 à 97
            'pays' => 'fra', //par défaut, on change que pour le cas spécifique
        );

        //base du calcul par défaut pour la clef (est modifié pour la corse)
        $aChecker = floatval(substr($value, 0, 13));

        /* Traitement des cas des personnes nées hors métropole ou en corse */
        switch (true)
        {
            //départements corses. Le calcul de la cles est différent
            case $return['departement'] == '2A':
                $aChecker = floatval(str_replace('A', 0, substr($value, 0, 13)));
                $aChecker -= 1000000;
                break;

            case $return['departement'] == '2B':
                $aChecker = floatval(str_replace('B', 1, substr($value, 0, 13)));
                $aChecker -= 2000000;
                break;

            case $return['departement'] == 97 || $return['departement'] == 98:
                $return['departement'] .= substr($return['numcommun'], 0, 1);
                $return['numcommun'] = substr($return['numcommun'], 1, 2);
                if ($return['numcommun'] > 90)
                {
                    //90 = commune inconnue
                    return false;
                }
                break;

            case $return['departement'] == 99:
                $return['pays'] = $match['numcommune'];
                if ($return['numcommun'] > 990)
                {
                    //990 = pays inconnu
                    return false;
                }
                break;

            default:
                if (isset($return['numcommun']))
                {
                    if ($return['numcommun'] > 990)
                    {
                        //990 = commune inconnue
                        return false;
                    }
                }
                break;
        }

        $clef = 97 - fmod($aChecker, 97);

        if (empty($return['clef']))
        {
            $return['clef'] = $clef; //la clef est optionnelle, si elle n'est pas spécifiée, le numéro est valide, mais on rajoute la clef
        }
        if ($clef != $return['clef'])
        {
            return false;
        }

        return true;
    }

}
