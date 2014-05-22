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
 * @date 17/04/14.04.2014 18:20
 */

namespace Mindy\Form;

use Exception;

abstract class ModelForm extends BaseForm
{
    public $ormClass = '\Mindy\Orm\Model';

    public $instance;

    public function init()
    {
        parent::init();
        if($this->instance) {
            $this->setInstance($this->instance);
        }
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $instance = $this->getInstance();

        $this->clearErrors();
        $instance->clearErrors();

        /* @var $field \Mindy\Orm\Fields\Field */
        $fields = $this->getFieldsInit();

        if(!$instance->isValid()) {
            foreach($instance->getErrors() as $key => $errors) {
                foreach($errors as $error) {
                    if(array_key_exists($key, $fields)) {
                        $fields[$key]->addError($error);
                    }
                }
            }
        }

        foreach ($fields as $name => $field) {
            $errors = $field->getErrors();
            if(empty($error)) {
                if ($field->isValid() === false) {
                    foreach ($field->getErrors() as $error) {
                        $this->addError($name, $error);
                    }
                }
            } else {
                foreach($errors as $error) {
                    $this->addError($name, $error);
                }
            }
        }

        return $this->hasErrors() === false;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        parent::setData($data);
        $this->getInstance()->setData($data);
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setFiles(array $data)
    {
        parent::setFiles($data);
        $this->getInstance()->setData($data);
        return $this;
    }

    /**
     * @param $model \Mindy\Orm\Model
     * @return $this
     * @throws \Exception
     */
    public function setInstance($model)
    {
        if(is_subclass_of($model, $this->ormClass) || $model instanceof IFormModel) {
            $this->instance = $model;
            /* @var $model \Mindy\Orm\Model */
            foreach($model->getFieldsInit() as $name => $field) {
                if (is_a($field, $model->autoField)) {
                    continue;
                }

                if($this->hasField($name)) {
//                    $fieldValue = $this->getField($name)->getValue();
//                    if(empty($fieldValue)) {
//                        $this->getField($name)->setValue($field->getValue());
//                    }
//                    if($name == 'view') {
//                        d($model->{$name});
//                    }
                    $this->getField($name)->setValue($model->{$name});
                }
            }
            return $this;
        }

        throw new Exception("Please use Mindy\\Orm\\Model or IFormModel");
    }

    /**
     * @return \Mindy\Orm\Model
     */
    public function getInstance()
    {
        if(!$this->instance) {
            $this->instance = $this->getModel();
        }
        return $this->instance;
    }

    public function save()
    {
        return $this->getInstance()->save();
    }

    abstract public function getModel();
}
