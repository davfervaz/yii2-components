<?php

namespace dlds\components\behaviors\db;

use Yii;
use yii\base\ErrorException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class ManyToManyBehavior
 * @package namespace dlds\components
 *
 * This behavior makes it easy to maintain
 * relations many-to-many in ActiveRecord model.
 *
 * Usage:
 * 1. Add new validation rule for new attributes
 * 2. Add config behavior in your model and set array relations
 *
 * These attributes are used in the ActiveForm.
 * They are created automatically.
 * $this->users_list;
 * $this->$tasks_list;
 * Example:
 * <?= $form->field($model, 'users_list')
 *      ->dropDownList($users, ['multiple' => true]) ?>
 *
 * public function rules()
 * {
 *     return [
 *         [['users_list', 'tasks_list'], 'safe']
 *     ];
 * }
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => \dlds\components\behaviors\db\ManyToManyBehavior::className(),
 *             'relations' => [
 *                 'users_list' => 'users',
 *                 'tasks_list' => [
 *                     'tasks',
 *                     'set' => function($tasksList) {
 *                         return JSON::decode($tasksList);
 *                     },
 *                     'get' => function($value) {
 *                         return JSON::encode($value);
 *                     }
 *                 ]
 *             ],
 *         ],
 *     ];
 * }
 *
 * public function getUsers()
 * {
 *     return $this->hasMany(User::className(), ['id' => 'user_id'])
 *         ->viaTable('{{%object_has_user}}', ['object_id' => 'id']);
 * }
 *
 * public function getTasks()
 * {
 *     return $this->hasMany(Task::className(), ['id' => 'user_id'])
 *         ->viaTable('{{%object_has_task}}', ['object_id' => 'id']);
 * }
 */
class ManyToManyBehavior extends \yii\base\Behavior
{

    /**
     * Default primary key name
     */
    const DF_PRIMARY_KEY = 'id';

    /**
     * Relations list
     * @var array
     */
    public $relations = [];

    /**
     * Related records entries keys
     * @var array
     */
    private $_keys = [];

    /**
     * Cached related records
     * @var array
     */
    private $_records = [];

    /**
     * Events list
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'handleManyToMany',
            ActiveRecord::EVENT_AFTER_UPDATE => 'handleManyToMany',
        ];
    }

    /**
     * Save many to many relations value in data base
     * @param $event
     * @throws ErrorException
     * @throws \yii\db\Exception
     */
    public function handleManyToMany($event)
    {
        $pkOwner = $this->_pkOwner();

        // go through all m2m configs
        foreach ($this->relations as $attr => $config) {

            // skip when no value is set for current relation
            $junctions = ArrayHelper::getValue($this->_keys, $attr, false);

            if (false === $junctions) {
                continue;
            }

            $relationName = $this->_relName($config);
            $relation = $this->owner->getRelation($relationName);

            if (empty($relation->via)) {
                throw new ErrorException("This attribute \"{$relationName}\" is not Many-to-Many relation");
            }

            $df = $this->_relDefinition($relation);

            // prepare rows to be inserted
            $rows = [];

            foreach ($junctions as $pkRelated) {
                array_push($rows, [$pkOwner, $pkRelated]);
            }

            // make operation transactional
            $db = \Yii::$app->db;
            $transaction = $db->beginTransaction();

            try {

                // deletes current junctions
                $this->_delJunctions($df[0], $df[2], $pkOwner, $db);

                // create new junctions
                $this->_addJunctions($df[0], $df[2], $df[1], $rows, $db);

                $transaction->commit();
            } catch (\yii\db\Exception $ex) {
                $transaction->rollback();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (!ArrayHelper::keyExists($name, $this->relations)) {
            return parent::canGetProperty($name, $checkVars);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (!ArrayHelper::keyExists($name, $this->relations)) {
            return parent::canSetProperty($name, $checkVars);
        }

        return true;
    }

    /**
     * Retrieves keys for given relational attr name
     * @param string $attr
     * @return array
     */
    public function m2mKeys($attr)
    {
        return ArrayHelper::getValue($this->_keys, $attr, []);
    }

    /**
     * Retrieves records for given relational attr name
     * @param string $attr
     * @return array
     */
    public function m2mRecords($attr)
    {
        return ArrayHelper::getValue($this->_records, $attr, []);
    }

    /**
     * Retrieves records for given relational attr name
     * @param string $attr
     * @return array
     */
    public function m2mDbRead($attr)
    {
        $relName = $this->_relName($this->_relConfig($attr));

        $this->owner->{$attr} = $this->owner->getRelation($relName);
    }

    /**
     * Retrieves records for given relational attr name
     * @param string $attr
     * @return array
     */
    public function m2mDbRemove($attr)
    {
        $relName = $this->_relName($this->_relConfig($attr));
        $relation = $this->owner->getRelation($relName);

        $pkOwner = $this->_pkOwner();
        $df = $this->_relDefinition($relation);

        // deletes current junctions
        $this->_delJunctions($df[0], $df[2], $pkOwner);
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (!ArrayHelper::keyExists($name, $this->relations)) {
            return parent::__get($name);
        }

        $keys = ArrayHelper::getValue($this->_keys, $name, []);

        if (empty($keys)) {
            $this->m2mDbRead($name);
        }

        $keys = ArrayHelper::getValue($this->_keys, $name, []);

        $callback = $this->_relConfig($name, 'get');

        if ($callback && is_callable($callback)) {
            return call_user_func($callback, $keys);
        }

        return $keys;
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (!ArrayHelper::keyExists($name, $this->relations)) {
            parent::__set($name, $value);
        }

        $callback = $this->_relConfig($name, 'set');

        if ($callback &&  is_callable($callback)) {
            $this->_keys[$name] = call_user_func($callback, $value);
            return;
        }

        // find and cache entries when ActiveQuery is given
        if ($value instanceof \yii\db\ActiveQuery) {
            $pk = call_user_func([$value->modelClass, 'primaryKey']);
            $pkName = ArrayHelper::getValue($pk, 0, self::DF_PRIMARY_KEY);

            $this->_records[$name] = $value->all();
            $value = ArrayHelper::map($this->_records[$name], $pkName, $pkName);
        }

        // fill keys of related records
        $this->_keys[$name] = $value;
    }

    /**
     * Retrieves owner primary key
     * @return int
     * @throws ErrorException
     */
    private function _pkOwner()
    {
        $pkOwner = $this->owner->getPrimaryKey();

        if (is_array($pkOwner)) {
            throw new ErrorException("This behavior not supported composite primary key");
        }

        return $pkOwner;
    }

    /**
     * Deletes all junction records
     * @param string $tblJunction
     * @param string $colOwner
     * @param string|int $pkOwner
     * @param \yii\db\Connection $db
     */
    private function _addJunctions($tblJunction, $colOwner, $colRelated, array $rows, \yii\db\Connection $db = null)
    {
        if (!$db) {
            $db = \Yii::$app->db;
        }

        // create new junctions
        return $db->createCommand()
            ->batchInsert($tblJunction, [$colOwner, $colRelated], $rows)
            ->execute();
    }

    /**
     * Deletes all junction records
     * @param string $tblJunction
     * @param string $colOwner
     * @param string|int $pkOwner
     * @param \yii\db\Connection $db
     */
    private function _delJunctions($tblJunction, $colOwner, $pkOwner, \yii\db\Connection $db = null)
    {
        if (!$db) {
            $db = \Yii::$app->db;
        }
        // remove current junctions
        return $db->createCommand()
            ->delete($tblJunction, "{$colOwner} = :pk", [':pk' => $pkOwner])
            ->execute();
    }

    /**
     * Get source attribute name
     * @param $attr
     * @return null
     */
    private function _relName($config)
    {
        if (!is_array($config)) {
            return $config;
        }

        return ArrayHelper::getValue($config, 0);
    }

    /**
     * Get relation param
     * @param $attr
     * @return mixed
     * @throws ErrorException
     */
    private function _relConfig($attr, $param = null)
    {
        if (!ArrayHelper::keyExists($attr, $this->relations)) {
            throw new ErrorException("ManyToMany Relation \"{$attr}\" is not configured");
        }

        if (!$param) {
            return $this->relations[$attr];
        }

        return ArrayHelper::getValue($this->relations[$attr], $param);
    }

    /**
     * Retrieves relation definition
     * ---
     * Definition is sorted array of following names: ['tblJunction', 'colRelated', 'colOwner']
     * ---
     * @param \yii\db\ActiveQuery $relation
     * @return array
     */
    private function _relDefinition($relation)
    {
        list($tblJunction) = array_values($relation->via->from);
        list($colRelated) = array_values($relation->link);
        list($colOwner) = array_keys($relation->via->link);

        return [$tblJunction, $colRelated, $colOwner];
    }

}
