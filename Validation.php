<?php

/**
 * @script: Validation.php
 * @author: Mahadi Hasan
 * @E-mail: mhm2k9@gmail.com
 * @time: 05/10/2016 09:26:35 PM
 */

class Validation
{
    const VALIDATE_STRING = 1;
    const VALIDATE_INT = 2;
    const VALIDATE_FLOAT = 3;
    const VALIDATE_REGEX = 4;
    const VALIDATE_ARRAY = 5;
    const VALIDATE_JSON = 6;
    const VALIDATE_EMAIL = 7;
    const VALIDATE_DATE = 8;
    const DATE_MMDDYYYY = 9;
    const DATE_TIME = 10;
    const DATE_DDMMYYYY = 11;


    /**
     * @param $value <p>Value that will be validated</p>
     * @param $type <p>Validation type, ie., int, float, string etc</p>
     * @param null $default <p>The value that will be returned if validation is failed</p>
     * @param null $pattern <p>Match patter. ie., regex or date format</p>
     * @return mixed|string <p>Returns the validated value</p>
     */
    public function preventAttack( $value, $type, $default = null, $pattern = null )
    {
        switch ( $type )
        {
            case self::VALIDATE_STRING:
                $value = is_string( $value ) ? htmlspecialchars( stripslashes( trim( $value ) ) ) : $default;
                break;

            case self::VALIDATE_INT:
                $value = filter_var($value, FILTER_VALIDATE_INT);
                $value = $value || $value === 0 ? $value * 1 : $default;
                break;

            case self::VALIDATE_FLOAT:
                $value = filter_var($value, FILTER_VALIDATE_FLOAT);
                $value = $value || $value === 0 ? $value * 1 : $default;
                break;

            case self::VALIDATE_REGEX:
                $value = filter_var($value, FILTER_VALIDATE_REGEXP, [ 'options' => [ 'regexp' => $pattern ] ] ) ? $value : $default;
                break;

            case self::VALIDATE_ARRAY:
                $value = is_array( $value ) ? $value : $default;
                break;

            case self::VALIDATE_JSON:
                @json_decode( $value );
                $value = ( json_last_error() == JSON_ERROR_NONE ) ? $value : $default;
                break;

            case self::VALIDATE_EMAIL:
                $value = trim( $value );
                $value = filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : $default;
                break;

            case self::VALIDATE_DATE:
                $dateElements = [];
                $defaultTime = '';

                if ( is_string( $value ) )
                {
                    $year       =   '[0-9]{4}';
                    $month      =   '0[1-9]|1[0-2]';
                    $day        =   '0[1-9]|[1-2][0-9]|3[0-1]';
                    $time       =   '[0-5][0-9]';
                    $separator  =   '[-\/.]';
                    $time       =   "\s$time:$time:$time";

                    if ( preg_match("/($year)$separator($month)$separator($day)($time)?/", $value, $match) ) # YYYYMMDD
                    {
                        $dateElements = [ $match[ 1 ], $match[ 2 ], $match[ 3 ] ];

                        isset( $match[ 4 ] ) && $defaultTime = $match[ 4 ];
                    }
                    elseif ( preg_match("/($month)$separator($day)$separator($year)($time)?/", $value, $match) ) # MMDDYYYY
                    {
                        $dateElements = [ $match[ 3 ], $match[ 1 ], $match[ 2 ] ];

                        isset( $match[ 4 ] ) && $defaultTime = $match[ 4 ];
                    }
                    elseif ( preg_match("/($day)$separator($month)$separator($year)($time)?/", $value, $match) ) # DDMMYYYY
                    {
                        $dateElements = [ $match[ 3 ], $match[ 2 ], $match[ 1 ] ];

                        isset( $match[ 4 ] ) && $defaultTime = $match[ 4 ];
                    }
                }

                if ( !empty( $dateElements ) )
                {
                    switch ( $pattern )
                    {
                        case self::DATE_MMDDYYYY:
                            $value = $dateElements[ 1 ] . '-' . $dateElements[ 2 ] . '-' . $dateElements[ 0 ];
                            break;

                        case self::DATE_TIME:
                            $value = implode( '-', $dateElements ) . $defaultTime;
                            break;

                        case self::DATE_DDMMYYYY:
                            $value = implode( '-', array_reverse( $dateElements ) );
                            break;

                        default:
                            $value = implode( '-', $dateElements );
                    }
                }
                else
                {
                    $value = $default;
                }

                break;
        }

        return $value;
    }

    /**
     * @param $parameterName <p>GET parameter name. $_GET['name'], here `name` is parameterName</p>
     * @param $valueType <p>Validation type, int, float, date, regex etc</p>
     * @param null $defaultValue <p>Take default value if no parameter is found</p>
     * @param null $pattern <p>Pattern that will be matched with value</p>
     * @return mixed|string <p>Return value after validation</p>
     */
    public function secureGetValue( $parameterName, $valueType, $defaultValue = null, $pattern = null )
    {
        $value = isset( $_GET[ $parameterName ] ) ? $_GET[ $parameterName ] : $defaultValue;

        return $this->preventAttack( $value, $valueType, $defaultValue, $pattern );
    }

    /**
     * @param $parameterName <p>POST parameter name. $_POST['name'], here `name` is parameterName</p>
     * @param $valueType <p>Validation type, int, float, date, regex etc</p>
     * @param null $defaultValue <p>Take default value if no parameter is found</p>
     * @param null $pattern <p>Pattern that will be matched with value</p>
     * @return mixed|string <p>Return value after validation</p>
     */
    public function securePostValue( $parameterName, $valueType, $defaultValue = null, $pattern = null )
    {
        $value = isset( $_POST[ $parameterName ] ) ? $_POST[ $parameterName ] : $defaultValue;

        return $this->preventAttack( $value, $valueType, $defaultValue, $pattern );
    }

    /**
     * @param $parameterName <p>COOKIE parameter name. $_COOKIE['name'], here `name` is parameterName</p>
     * @param $valueType <p>Validation type, int, float, date, regex etc</p>
     * @param null $defaultValue <p>Take default value if no parameter is found</p>
     * @param null $pattern <p>Pattern that will be matched with value</p>
     * @return mixed|string <p>Return value after validation</p>
     */
    public function secureCookieValue( $parameterName, $valueType, $defaultValue = null, $pattern = null )
    {
        $value = isset( $_COOKIE[ $parameterName ] ) ? $_COOKIE[ $parameterName ] : $defaultValue;

        return $this->preventAttack( $value, $valueType, $defaultValue, $pattern );
    }
}

