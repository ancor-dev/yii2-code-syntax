<?php
/**
 * Created by Anton Korniychuk <ancor.dev@gmail.com>.
 */
namespace ancor\codeSyntax;

use Yii;
use yii\validators\Validator;


/**
 * # Usage
 * ```php
 * use ancor\codeSyntax\SqlSyntaxValidator;
 *
 * public function rules()
 * {
 *     return [
 *        [['sqlCodeField'], SqlSyntaxValidator::className()],
 *     ];
 * }
 * ```
 * or with options
 * ```php
 * use ancor\codeSyntax\SqlSyntaxValidator;
 *
 * public function rules()
 * {
 *     return [
 *        [
 *            ['sqlCodeField'],
 *            SqlSyntaxValidator::className(),
 *            // 'wrapper' => '...{{part}}...',
 *            // 'makeSql' => function($validator, $partSql) { ... }
 *            'message' => 'Field {attribute} is invalid',
 *        ],
 *     ];
 * }
 * ```
 */
class SqlSyntaxValidator extends Validator
{
    /**
     * @var string wrapper for sql part. Default wrapper is not used. Simple '{{part}}'
     * @example:
     *   SELECT
     *     {{part}} AS `result`
     *   FROM `user`
     *     INNER JOIN `access` ON `user_id` = `user`.`id`
     */
    public $wrapper = '{{part}}';

    /**
     * @var string error message
     */
    public $message;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->message = Yii::t('app', '{attribute} has sql syntax error: {error}');
    } // end init()

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $sql = $this->makeSql($model->$attribute);
        try {
            Yii::$app->db->createCommand($sql)->queryOne();
        } catch (\Exception $e) {
            $this->addCustomError($model, $attribute, $e);
        }
    } // end validateAttribute()

    /**
     * make error message. You can redeclare this method for passing custom parameters in message template
     *
     * @param \yii\base\Model $model
     * @param string          $attribute
     * @param \Exception      $exception
     */
    protected function addCustomError($model, $attribute, \Exception $exception)
    {
        $this->addError($model, $attribute, $this->message, [
            'error' => $exception->getMessage(),
            'code'  => $exception->getCode(),
        ]);
    } // end addCustomError()

    /**
     * @var callable a PHP callable that will be called to return full sql command for
     * the specified sqlPart key value. If not set, [[makeSql()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($validator, $partSql) {
     *     // $validator is current instance of the validator
     *     // $partSql is embedded sql code
     * }
     * ```
     *
     * The callable should return full sql query
     */
    public $makeSql;

    /**
     * make sql query.
     * 1) Apply wrapper
     * 2) Prefix 'EXPLAIN '
     *
     * @param string $sqlPart
     *
     * @return string
     */
    protected function makeSql($sqlPart)
    {
        if ($this->makeSql) {
            $sql = call_user_func($this->makeSql, $this, $sqlPart);
        } else {
            $sql = str_replace('{{part}}', $sqlPart, $this->wrapper);
        }

        return "EXPLAIN $sql";
    } // end makeSql()

}