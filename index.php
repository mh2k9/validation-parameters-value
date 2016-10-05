<?php
/**
 * @script: Validation.php
 * @author: Mahadi Hasan
 * @E-mail: mhm2k9@gmail.com
 * @time: 05/10/2016 10:20:10 PM
 */

# include validation class
require_once('Validation.php');

# create instance / object of validation class
$validate = new Validation();

#-----------------------------------------------#
#                                               #
#               Filter GET value                #
#                                               #
#-----------------------------------------------#

$_GET = [
    'username' => ' A string',
];
echo $validate->secureGetValue('username', $validate::VALIDATE_STRING); # return 'A string'
echo '<br/>';

#-----------------------------------------------#
#                                               #
#               Filter POST value               #
#                                               #
#-----------------------------------------------#

$_POST = [
    'id' => ' 2',
    'page' => '',
    'email' => 'example@email.com',
];

echo $validate->securePostValue('id', $validate::VALIDATE_INT); # returns 2
echo '<br/>';

echo $validate->securePostValue('page', $validate::VALIDATE_INT, 'Nothing is found'); # returns Nothing is found
echo '<br/>';

echo $validate->securePostValue('offset', $validate::VALIDATE_INT, 0); # returns 0
echo '<br/>';

echo $validate->securePostValue('limit', $validate::VALIDATE_INT, 50); # returns 50
echo '<br/>';

echo $validate->securePostValue('email', $validate::VALIDATE_EMAIL); # returns example@email.com
echo '<br/>';

#-----------------------------------------------#
#                                               #
#                Validate values                #
#                                               #
#-----------------------------------------------#

echo $validate->preventAttack( '2015-01-01', $validate::VALIDATE_DATE); # returns 2015-01-01
echo '<br/>';

echo $validate->preventAttack( '01/01/2015', $validate::VALIDATE_DATE); # returns 2015-01-01
echo '<br/>';

echo $validate->preventAttack( '09.26.2016', $validate::VALIDATE_DATE); # returns 2016-09-26
echo '<br/>';

echo $validate->preventAttack( '2015.09.15', $validate::VALIDATE_DATE, null, $validate::DATE_MMDDYYYY); # returns 09-15-2015
echo '<br/>';

# Regex validation
echo $validate->preventAttack( '12345', $validate::VALIDATE_REGEX, null, '/^\d+$/'); # returns 12345
echo '<br/>';