<?php

namespace common\models\forms;

use common\models\one_c\Order;
use common\models\one_c\OrderItems;
use Yii;
use yii\base\Model;

class OrderForm extends Model
{
    private $_order;
    private $_items;
    const SCENARIO_AUTO = 0;
    const SCENARIO_SELF_PICKUP = 1;
    const SCENARIO_RAILWAY = 2;

    public function scenarios()
    {
        return [
            self::SCENARIO_SELF_PICKUP => [

            ],
            self::SCENARIO_AUTO => [

            ],
            self::SCENARIO_RAILWAY => [

            ],
        ];
    }

    public function rules()
    {
        return [
            [['Order'], 'required'],
            [['Items'], 'safe'],
        ];

    }

    public function afterValidate()
    {
        if (!Model::validateMultiple($this->getAllModels())) {
            $this->addError(null); // add an empty error to prevent saving
        }
        parent::afterValidate();
    }

    /**
     * @throws \yii\db\Exception
     */
    public function save($user_id)
    {
        if (empty($this->_order->delivery_type)) {
            $this->_order->delivery_type = $this->_order->scenario;
        }

        $this->_order->status = 0;
        $this->_order->user_id = $user_id;
        if(!$this->validate()) {

        }
        foreach ($this->getAllModels() as $model) {
            if (!$model->validate()) {
                return false;
            }
        }
        $this->_order->save();
        if (!$this->saveOrderItems($this->_order->id)) {
            return false;
        }
        return true;
    }

    public function saveOrderItems($order_id)
    {
        foreach ($this->_items as $order_item) {
            $order_item->order_id = $this->_order->id;
            if (!$order_item->save(false)) {
                return false;
            }
        }
        return true;
    }

    public function errorSummary($form)
    {
        $errorLists = [];
        foreach ($this->getAllModels() as $id => $model) {
            $errorList = $form->errorSummary($model, [
                'header' => '<p>Please fix the following errors for <b>' . $id . '</b></p>',
            ]);
            $errorList = str_replace('<li></li>', '', $errorList); // remove the empty error
            $errorLists[] = $errorList;
        }
        return implode('', $errorLists);
    }

    public function setItems($orderItems)
    {
        $this->_items = [];
        foreach ($orderItems as $key => $orderItem) {
            $item = new OrderItems($orderItem);
            $item->scenario = $this->scenario;
            $this->_items[$key] = $item;
        }
    }

    public function setOrder($order)
    {
        $this->_order = new Order($order);
        $this->_order->scenario = $this->scenario;
    }

    public function getAllModels()
    {
        $models = [
            'Order' => $this->getOrder(),
        ];
        foreach ($this->getItems() as $id => $order_item) {
            $models['Items.' . $id] = $this->_items[$id];
        }
        return $models;
    }
    public function getOrder()
    {
        return $this->_order;
    }


    public function getItems()
    {
        if ($this->_items === null) {
            $item = new OrderItems();
            $item->scenario = $this->scenario;
            $this->_items = [$item];
        }
        return $this->_items;
    }

}