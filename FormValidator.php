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
        'minLength',
        'maxLength',
        'matches',
        'hasNoSpecialChars',
        'timeStamp',
        'yearMonth',
        'alphabetic',
        'alphaNumeric',
        'digit',
        'email',
        'url',
        'name',
        'required',
        'checkUnique'
    ];

    private $errors = [];
    private $failed = false;

    public $messages = [
        'minLength'         => 'The :field field must be a minimum of :satisfier length',
        'maxLength'         => 'The :field field must be a maximum of :satisfier length',
        'matches'           => 'The field :field must match the :field_match field',
        'hasNoSpecialChars' => 'No special characters allowed',
        'timeStamp'         => 'Must be a timestamp formatted as <em>YYYY-MM-DD</em>',
        'yearMonth'         => 'Must be time and year formatted as <em>YYYY-MM</em>',
        'alphabetic'        => 'Must only contain letters',
        'alphaNumeric'      => 'Must only contain letters and numbers',
        'digit'             => 'Must only contain numbers',
        'email'             => 'That is not a valid e-mail-address',
        'name'              => 'Must only contain letters and spaces',
        'required'          => 'The :field field is required',
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
