<?php
/**
 * Author: CHEVALIER Alexis <alexis.chevalier@supinfo.com>
 * Date: 04/02/13
 * File: view.php
 */

class View_Model
{
    /**
     * Variables passées a TWIG
     */
    private $data = array();

    /**
     * Twig renderer
     */
    private $renderer = FALSE;

    /**
     * Initialisation du template
     *
     * @param $template
     */
    public function __construct($template)
    {
        //compose file name
        $file = $template . '.twig';

        $loader = new Twig_Loader_Filesystem(SERVER_ROOT . '/views/'); // Dossier contenant les templates

        if (MODE == "DEVEL") {
            $twig = new Twig_Environment($loader, array(
                'cache' => false,
                'debug' => true,
            ));
            $twig->addExtension(new Twig_Extension_Debug());
        } else {
            $twig = new Twig_Environment($loader, array('cache' => false));
        }

        $twig->addGlobal('mode', MODE);
        $twig->addGlobal('site_url', SITE_ROOT);
        $twig->addGlobal('session', $_SESSION);

        if (file_exists(SERVER_ROOT . '/views/' . $file)) {
            /**
             * trigger render to include file when this model is destroyed
             * if we render it now, we wouldn't be able to assign variables
             * to the view!
             */

            $this->renderer = $twig->loadTemplate($file);
        }
    }

    /**
     * Stocke les arguments passés au template dans la variable data
     *
     * @param $variable
     * @param $value
     */
    public function assign($variable, $value)
    {
        $this->data[$variable] = $value;
    }

    /*
     * Le destructeur permet d'effectuer le rendu du template
     */

    public function __destruct()
    {
        //parse data variables into local variables, so that they render to the view

        echo $this->renderer->render(
            $this->data
        );
    }
}
