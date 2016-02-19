<?php
/**
 * Created by Anton Korniychuk <ancor.dev@gmail.com>.
 */
namespace ancor\codeSyntax;

use yii\validators\Validator;


/**
 * # Usage
 * ```php
 * use ancor\syntaxLint\PhpSyntaxValidator;
 *
 * public function rules()
 * {
 *     return [
 *        [['phpCodeField'], PhpSyntaxValidator::className()],
 *     ];
 * }
 * ```
 *
 * or with options
 * ```php
 * use ancor\syntaxLint\PhpSyntaxValidator;
 *
 * public function rules()
 * {
 *     return [
 *        [
 *            ['phpCodeField'],
 *            PhpSyntaxValidator::className(),
 *            'isWrapPhp' => true,
 *            'message' => 'Field {attribute} is invalid',
 *        ],
 *     ];
 * }
 * ```
 */
 class PhpSyntaxValidator extends Validator
{
    /**
     * @var string error message
     */
    public  $message;
    /**
     * @var boolean wrap php code with php-tags
     */
    public $isWrapPhp = true;

    /**
     * @var string command line result, after check
     */
    private $result;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->message = 'Field {attribute} has syntax errors: {errors}.';
    } // end init()

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if ( !$this->validatePhpCodeString($model->$attribute)) {
            $this->addError($model, $attribute, $this->message, ['errors' => $this->result]);
        }
    } // end validateAttribute()

    /**
     * Validate php code string
     *
     * @param string $code code to check
     *
     * @return bool
     */
    private function validatePhpCodeString($code)
    {
        if ($this->isWrapPhp) {
            $code = '<?php '.$code.'; ?>';
        }
        $this->result = trim(shell_exec("echo " . escapeshellarg($code) . " | php -l"));
        return $this->result == "No syntax errors detected in -";
    } // end validatePhpCodeString()

}