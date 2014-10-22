<?php
/**
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 22/08/14.08.2014 18:27
 */

namespace Mindy\Form\Tests;

use Exception;
use Mindy\Form\BaseForm;
use Mindy\Form\Fields\CharField;

class TestForm extends BaseForm
{
    public $templates = [
        'block' => '../templates/block.php',
        'table' => '../templates/table.php',
        'ul' => '../templates/ul.php',
    ];

    public function getFields()
    {
        return [
            'name' => [
                'class' => CharField::className()
            ]
        ];
    }

    public function getTemplateFromType($type)
    {
        if (array_key_exists($type, $this->templates)) {
            $template = $this->templates[$type];
        } else {
            throw new Exception("Template type {$type} not found");
        }
        $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . ltrim($template, DIRECTORY_SEPARATOR));
        if (!is_file($path)) {
            throw new Exception("File not found: {$path}");
        }
        return $path;
    }

    public function renderInternal($view, array $data = [])
    {
        ob_start();
        extract($data);
        include($view);
        return ob_get_clean();
    }
}
