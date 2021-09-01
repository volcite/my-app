<?php

namespace Core;

class View
{
    protected $base_dir;
    protected $defaults;
    protected $layout_variables = array();

    public function __construct($baseDir, $defaults = array())
    {
        $this->baseDir = $baseDir;
        $this->defaults = $defaults;
    }

    public function setLayoutVar($name, $value)
    {
        $this->layoutVariables[$name] = $value;
    }

    public function render($path, $variables = array(), $layout = false)
    {
        $file = $this->baseDir . '/' . $path . '.php';
        extract(array_merge($this->defaults, $variables));
        ob_start();
        ob_implicit_flush(0);
        require $file;
        $content = ob_get_clean();
        if ($layout) {
            $content = $this->render(
                $layout,
                array_merge($this->layoutVariables, array('_content' => $content))
            );
        }
        return $content;
    }

    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}