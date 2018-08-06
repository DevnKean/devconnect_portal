<?php
/**
 * ExceptionController.php
 * avanzu-admin
 * Date: 01.03.14
 */

namespace ThemeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;

use Symfony\Component\HttpFoundation\Request;

class ExceptionController extends \Symfony\Bundle\TwigBundle\Controller\ExceptionController {


    /**
     * @param Request $request
     * @param string  $format
     * @param int     $code
     * @param bool    $debug
     *
     * @return TemplateReference | string
     */
    protected function findTemplate(Request $request, $format, $code, $debug)
    {

        if(strpos($request->getPathInfo(), '/admin') !== 0) {
            return parent::findTemplate($request, $format, $code, $debug);
        }

        $name = $debug ? 'exception' : 'error';
        if ($debug && 'html' == $format) {
            $name = 'exception_full';
        }

        // when not in debug, try to find a template for the specific HTTP status code and format
        if (!$debug) {
            $template = new TemplateReference('ThemeBundle', 'Exception', $name.$code, $format, 'twig');
            if ($this->templateExists($template)) {
                return $template;
            }
        }

        // try to find a template for the given format
        $template = new TemplateReference('ThemeBundle', 'Exception', $name, $format, 'twig');
        if ($this->templateExists($template)) {
            return $template;
        }

        // default to a generic HTML exception
        $request->setRequestFormat('html');

        $template = new TemplateReference('ThemeBundle', 'Exception', $name, 'html', 'twig');
        if ($this->templateExists($template)) {
            return $template;
        }

        return parent::findTemplate($request, $format, $code, $debug);

    }


}