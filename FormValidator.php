<?php
/**
 * Form-Validator class.
 * Implements the Validator class.
 */
require_once '../validator/Validator.php';

class FormValidator extends Validator
{
    protected $errorHandler;
    protected $rules = [
        'isMinLength',
        'isMaxLength',
        'matches',
        'hasNoSpecialChars',
        'isTimeStamp',
        'isYearMonth',
        'isAlphabetic',
        'isAlphaNumeric',
        'isDigit',
        'isEmail',
        'isUrl',
        'isName',
        'isRequired',
        'checkUnique'
    ];

    private $errors = [];
    private $failed = false;

    public $messages = [
        'isMinLength'       => 'The :field field must be a minimum of :satisfier length',
        'isMaxLength'       => 'The :field field must be a maximum of :satisfier length',
        'matches'           => 'The field :field must match the :field_match field',
        'hasNoSpecialChars' => 'No special characters allowed',
        'isTimeStamp'       => 'Must be a timestamp formatted as <em>YYYY-MM-DD</em>',
        'isYearMonth'       => 'Must be time and year formatted as <em>YYYY-MM</em>',
        'isAlphabetic'      => 'Must only contain letters',
        'isAlphaNumeric'    => 'Must only contain letters and numbers',
        'isDigit'           => 'Must only contain numbers',
        'isEmail'           => 'That is not a valid e-mail-address',
        'isName'            => 'Must only contain letters and spaces',
        'isRequired'        => 'The :field field is required',
        'checkUnique'       => 'The field :field must be unique',
    ];

    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }

    public function check($items, $rules)
    {
        foreach ( $items as $item => $value )
        {
            if ( in_array($item, array_keys($rules)) )
            {
                $this->validate([
                    'field' => $item,
                    'value' => $value,
                    'rules' => $rules[$item]
                ]);
            }
        }
        return $this;
    }

    protected function validate($item)
    {
        $field = $item['field'];

        foreach ( $item['rules'] as $rule => $requirement )
        {
            // Make sure rule exists
            if ( in_array($rule, $this->rules) )
            {
                if ( !call_user_func_array( [ $this, $rule ], [ $field, $item['value'], $requirement ] ) )
                {
                    // Failed rules
                    $this->errorHandler->addError(
                        str_replace( [':field', ':requirement'], [$field, $requirement], $this->messages[$rule] ), $field
                    );
                }
            }
        }
    }

    public function errors()
    {
        return $this->errorHandler;
    }

    public function failed()
    {
        return $this->errorHandler->hasErrors();
    }

}
