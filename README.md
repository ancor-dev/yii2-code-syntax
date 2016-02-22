# Code Syntax validators for Yii 2

Check code in some programming languages for syntax errors.

**Currently supported languages:**
- [php](#php-syntax-validator)
- [sql](#sql-syntax-validator)
- [json](#json-syntax-validator)

Feel free to let me know what else you want added via:

- [Issues](https://github.com/ancor-dev/yii2-code-syntax/issues)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ php composer.phar require ancor/yii2-code-syntax
```

or add

```
"ancor/yii2-code-syntax": "dev-master"
```

to the `require` section of your `composer.json` file.

## Validators

### Sql Syntax Validator

**Validation occurs as follows:**

1. Get the data from the field model
2. Wrapping wrapper
3. Add at the start `EXPLAIN `
4. Execute sql query
5. If the returned error, add this errors to validation message

##### Basic usage

```php
use ancor\codeSyntax\SqlSyntaxValidator;
public function rules()
{
    return [
        [['sqlCodeField'], SqlSyntaxValidator::className()],
    ];
}
```

##### Advanced usage with options

```php
use ancor\codeSyntax\SqlSyntaxValidator;
public function rules()
{
    return [
        [
            ['sqlCodeField'],
            SqlSyntaxValidator::className(),
            // 'wrapper' => '...{{part}}...',
            // 'makeSql' => function($validator, $partSql) { ... }
            'message' => 'Field {attribute} is invalid',
        ],
    ];
}
```

### Php Syntax Validator

**Warning:** this validator use php cli. And if php has been not added to $PATH, validator will not work.

##### Basic usage

```php
use ancor\codeSyntax\PhpSyntaxValidator;

public function rules()
{
    return [
       [['phpCodeField'], PhpSyntaxValidator::className()],
    ];
}
```

##### Advanced usage with options

```php
use ancor\codeSyntax\PhpSyntaxValidator;

public function rules()
{
    return [
       [
           ['phpCodeField'],
           PhpSyntaxValidator::className(),
           'isWrapPhp' => true,
           'message' => 'Field {attribute} is invalid',
       ],
    ];
}
```

### Json Syntax Validator

**Warning:** this validator use `json_decode()` php function. And depends on php-json extension.

##### Basic usage

```php
use ancor\codeSyntax\JsonSyntaxValidator;

public function rules()
{
    return [
       [['jsonCodeField'], JsonSyntaxValidator::className()],
    ];
}
```

##### Advanced usage with options

```php
use ancor\codeSyntax\JsonSyntaxValidator;

public function rules()
{
    return [
       [
           ['jsonCodeField'],
           JsonSyntaxValidator::className(),
           'message' => 'Field {attribute} has invalid json. Code: {errCode}, Msg: {errMsg}',
       ],
    ];
}
```

