# Code Syntax validators for some programming languages in Yii 2

## Description

**Currently supported languages:**
- [php](#php-syntax-validator)
- [sql](#sql-syntax-validator)

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
3. Add in the start `EXPLAIN `
4. Execute sql query
5. If the returned error, add this errors to validation message

#### Basic usage

```php
use ancor\syntaxLint\SqlSyntaxValidator;
public function rules()
{
    return [
        [['sqlCodeField'], SqlSyntaxValidator::className()],
    ];
}
```

### Advanced usage with options

```php
use ancor\syntaxLint\SqlSyntaxValidator;
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

#### Basic usage

```php
use ancor\syntaxLint\PhpSyntaxValidator;

public function rules()
{
    return [
       [['phpCodeField'], PhpSyntaxValidator::className()],
    ];
}
```

### Advanced usage with options

```php
use ancor\syntaxLint\PhpSyntaxValidator;

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
