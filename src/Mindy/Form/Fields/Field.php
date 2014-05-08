<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 17/04/14.04.2014 18:21
 */

namespace Mindy\Form\Fields;

use Closure;
use Mindy\Core\Object;
use Mindy\Form\BaseForm;

abstract class Field extends Object
{
    /**
     * @var bool Технические аттрибуты для inline моделей
     */
    public $hidden = false;

    /**
     * @var bool Технические аттрибуты для inline моделей
     */
    public $delete = false;

    public $value;

    public $widget;

    public $inputType;

    public $template = "<input type='{type}' id='{id}' name='{name}'{html}/>";

    public $hint;

    public $type = 'text';

    public $html;

    /**
     * @var string html class for render hint
     */
    public $hintClass = 'form-hint-text';

    public $label;

    public $name;

    public $validators = [];

    /**
     * @var BaseForm
     */
    public $form;

    public $errorClass = 'error';

    public $_errors = [];

    private $_validatorClass = '\Mindy\Form\Validator\Validator';

    public function __toString()
    {
        return (string)$this->render();
    }

    public function setForm(BaseForm $form)
    {
        $this->form = $form;
        return $this;
    }

    public function render()
    {
        $label = $this->renderLabel();
        $input = strtr($this->template, [
            '{type}' => $this->type,
            '{id}' => $this->getId(),
            '{name}' => $this->getName(),
            '{value}' => $this->getValue(),
            '{html}' => $this->getHtmlAttributes()
        ]);

        $hint = $this->hint ? $this->renderHint() : '';
        $errors = $this->getErrors() ? $this->renderErrors() : '';
        return $label . $input . $hint . $errors;
    }

    public function getName()
    {
        $prefixes = $this->form->prefix;
        $name = $this->name;
        foreach ($prefixes as $prefix) {
            $name = $prefix . '[' . $this->form->getId() . '][' . $name . ']';
        }
        return $name;
    }

    public function getHtmlAttributes()
    {
        if (is_array($this->html)) {
            $html = '';
            foreach ($this->html as $name => $value) {
                $html .= " $name='$value'";
            }
            return implode(' ', $this->html);
        } else {
            return $this->html;
        }
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getId()
    {
        return $this->form->getId() . '_' . $this->name;
    }

    public function renderLabel()
    {
        $label = $this->label ? $this->label : ucfirst($this->name);
        return strtr("<label for='{for}'>{label}</label>", [
            '{for}' => $this->id,
            '{label}' => $label
        ]);
    }

    public function renderErrors()
    {
        $errors = "";
        foreach ($this->getErrors() as $error) {
            $errors .= "<li>{$error}</li>";
        }

        return "<ul class='{$this->errorClass}'>{$errors}</ul>";
    }

    public function renderHint()
    {
        return strtr('<p class="{class}">{hint}</p>', [
            '{class}' => $this->hintClass,
            '{hint}' => $this->hint
        ]);
    }

    public function clearErrors()
    {
        $this->_errors = [];
    }

    public function isValid()
    {
        $this->clearErrors();

        foreach ($this->validators as $validator) {
            if ($validator instanceof Closure) {
                /* @var $validator \Closure */
                $valid = $validator->__invoke($this->value);
                if ($valid !== true) {
                    if (!is_array($valid)) {
                        $valid = [$valid];
                    }

                    $this->addErrors($valid);
                }
            } else if (is_subclass_of($validator, $this->_validatorClass)) {
                /* @var $validator \Mindy\Form\Validator\Validator */
                $validator->clearErrors();

                if ($validator->validate($this->value) === false) {
                    $this->addErrors($validator->getErrors());
                }
            }
        }

        return $this->hasErrors() === false;
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    public function addErrors($errors)
    {
        $this->_errors = array_merge($this->_errors, $errors);
    }

    public function addError($error)
    {
        $this->_errors[] = $error;
    }

    /**
     * Format:
     * [
     *     "Main" => [
     *         "Name", "Url", "Content"
     *     ],
     *     "Extra" => [ ... ]
     * ]
     * @return array
     */
    public function getFieldSets()
    {
        return [];
    }
}