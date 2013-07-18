<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alexischevalier
 * Date: 22/02/13
 * Time: 15:42
 * To change this template use File | Settings | File Templates.
 */
class Stats_Model
{
    /**
     * Retourne les statistiques d'une URL
     * @param $id
     * @return array|null
     */
    public function getStats($id)
    {
        $empty = true;
        $pdo = db::get_pdo_instance();
        $sth = $pdo->prepare('SELECT * FROM  2web_stats WHERE id_url = :idurl ORDER BY date_clicked');
        $sth->bindParam('idurl', $id);
        $sth->execute();

        $referers = array();
        $countries = array();
        $clicks = array();

        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($referers[$row['referrer']])) {
                $referers[$row['referrer']] = 0;
            }
            if (!isset($countries[$row['country_code']])) {
                $countries[$row['country_code']] = 0;
            }
            if (!isset($clicks[$row['date_clicked']])) {
                $clicks[$row['date_clicked']] = 0;
            }
            $referers[$row['referrer']] += 1;
            $countries[$row['country_code']] += 1;
            $clicks[$row['date_clicked']] += 1;
            $empty = false;
        }
        if ($empty == true) {
            return null;
        } else {
            $clicksString = '[';

            foreach ($clicks as $key => $clicks) {
                $date = explode('-', $key);
                $clicksString .= "[Date.UTC($date[0], $date[1], $date[2]),$clicks],";
            }
            $clicksString .= ']';

            $referersString = '[';
            foreach ($referers as $key => $clicks) {
                $referersString .= '["' . $key . '",' . $clicks . '],';
            }
            $referersString .= ']';

            $countriesString = '[';
            foreach ($countries as $key => $clicks) {
                $countriesString .= '["' . $key . '",' . $clicks . '],';
            }
            $countriesString .= ']';
            return array('clicks' => $clicksString, 'countries' => $countriesString, 'referers' => $referersString);
        }
    }

}
