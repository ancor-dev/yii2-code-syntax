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
 * use ancor\codeSyntax\JsonSyntaxValidator;
 *
 * public function rules()
 * {
 *     return [
 *        [['jsonCodeField'], JsonSyntaxValidator::className()],
 *     ];
 * }
 * ```
 *
 * or with options
 * ```php
 * use ancor\codeSyntax\JsonSyntaxValidator;
 *
 * public function rules()
 * {
 *     return [
 *        [
 *            ['jsonCodeField'],
 *            JsonSyntaxValidator::className(),
 *            'message' => 'Field {attribute} has invalid json. Code: {errCode}, Msg: {errMsg}',
 *        ],
 *     ];
 * }
 * ```
 */
class JsonSyntaxValidator extends Validator
{
    /**
     * @var string error message
     */
    public  $message;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->message = Yii::t('app', 'Field {attribute} has invalid json-code');
    } // end init()

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        json_decode($model->$attribute);

        if ($errCode = json_last_error()) {
            $this->addError($model, $attribute, $this->message, [
                'errCode' => $errCode,
                'errMsg'  => json_last_error_msg(),
            ]);
        }
    } // end validateAttribute()

}