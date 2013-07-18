<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 16/02/13
 * File: db.php
 */

/**
 * @return PDO|string
 * Retourne un objet PDO ou retourne un message d'erreur
 */
class Db
{
    public static function get_pdo_instance()
    {
        if (MODE == 'DEVEL') {
            $dsn = 'mysql:dbname=' . \config::Get('db_dev', 'dbname') . ';host=' . \config::Get('db_dev', 'host') . ';charset=utf8';
            $user = \config::Get('db_dev', 'user');
            $password = \config::Get('db_dev', 'password');

            try {
                $pdo = new PDO($dsn, $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $pdo;
            } catch (PDOException $e) {
                return 'Connexion à la base de donnée échouée : ' . $e->getMessage();
            }
        } else {
            $dsn = 'mysql:dbname=' . \config::Get('db_prod', 'dbname') . ';host=' . \config::Get('db_prod', 'host') . ';charset=utf8';
            $user = \config::Get('db_prod', 'user');
            $password = \config::Get('db_prod', 'password');

            try {
                return new PDO($dsn, $user, $password);
            } catch (PDOException $e) {
                return 'Probléme de connexion à la base de données !';
            }
        }
    }
}
