SupLink
=======
<pre>
Ce projet utilise un micro-framework MVC réalisé par mes soins, 
il est encore en cours de développement.
</pre>

Pour la configuration du projet en local:

MYSQL:
Création d'une base (utf8_unicode_ci)
Importer le fichier base.sql (racine du projet)

APACHE: 
Activer mod ssl, mod rewrite

PHP :
Activer mod pdo mysql, mod ssl, curl

Composer :
Installer les vendors

Framework : 
Configurer l'array de configurations dans /config/config.php (Exemple dans config.php.default)
