# validation-parameters-value

```php
# include validation class
require_once('Validation.php');

# create instance / object of validation class
$validate = new Validation();
```

#  Filter GET value
```php
$_GET = [
    'username' => ' A string',
];

$validate->secureGetValue('username', $validate::VALIDATE_STRING); # return 'A string'
```

# Filter POST value

```php
$_POST = [
    'id' => ' 2',
    'page' => '',
    'email' => 'example@email.com',
];

$validate->securePostValue('id', $validate::VALIDATE_INT); # returns 2

$validate->securePostValue('page', $validate::VALIDATE_INT, 'Nothing is found'); # returns Nothing is found

$validate->securePostValue('offset', $validate::VALIDATE_INT, 0); # returns 0

$validate->securePostValue('limit', $validate::VALIDATE_INT, 50); # returns 50

echo $validate->securePostValue('email', $validate::VALIDATE_EMAIL); # returns example@email.com
```

# Validate values

```php
$validate->preventAttack( '2015-01-01', $validate::VALIDATE_DATE); # returns 2015-01-01

$validate->preventAttack( '01/01/2015', $validate::VALIDATE_DATE); # returns 2015-01-01

$validate->preventAttack( '09.26.2016', $validate::VALIDATE_DATE); # returns 2016-09-26

$validate->preventAttack( '2015.09.15', $validate::VALIDATE_DATE, null, $validate::DATE_MMDDYYYY); # returns 09-15-2015

# Regex validation
$validate->preventAttack( '12345', $validate::VALIDATE_REGEX, null, '/^\d+$/'); # returns 12345
```
