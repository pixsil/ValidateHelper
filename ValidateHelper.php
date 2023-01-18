<?php

// version 15 - fixed numbers max to max_digits
// version 14.1 - add fallback for type
// version 14 - changed integer to numeric

if (!function_exists('validate')) {
    function validate($type, $other_validation = []) {
        // init (works for array or string)
        $validation_arr = is_string($other_validation) ? explode('|', $other_validation) : $other_validation;

        // guard: present must not check other stuff
        if (in_array('present', $validation_arr)) {
            return ['present'];
        }

        // set keys to no override
        foreach ($validation_arr as $key => $validation) {

            // explode for key
            $validation_explode = explode(':', $validation);

            // set key
            $validation_arr[$validation_explode[0]] = $validation;

            // remove old value
            unset($validation_arr[$key]);
        }

        // sometimes of required
        // if (in_array('nullable', $validation_arr)) {
        //     $extra_arr = ['nullable' => 'nullable'];
        // } elseif (in_array('sometimes', $validation_arr)) {
        //     $extra_arr = ['sometimes' => 'sometimes'];
        // } else {
        //     $extra_arr = ['required' => 'required'];
        // }

        // add
        switch ($type) {

            // input
            case 'input':
            case 'varchar':
            case 'string':
                $extra_arr['string'] = 'string';
                $extra_arr['max'] = 'max:255';
                break;

            // password
            case 'password':
                $extra_arr['string'] = 'string';
                $extra_arr['min'] = 'min:8';
                $extra_arr['max'] = 'max:255';
                $extra_arr['confirmed'] = 'confirmed';
                break;

            // textarea
            case 'text':
            case 'textarea':
                $extra_arr['max'] = 'max:65535';
                break;

            // checkbox or array
            case 'array':
            case 'checkbox':
                $extra_arr['array'] = 'array';
                break;

            // integer (whole numbers)
            case 'integer':
            case 'number':
            case 'int':
                $extra_arr['integer'] = 'integer';
                $extra_arr['max'] = 'max_digits:2147483647';
                break;

            // numeric (numbers with floating point)
            case 'numeric':
                $extra_arr['numeric'] = 'numeric';
                $extra_arr['max'] = 'max_digits:2147483647';
                break;

            // float or decimal
            case 'float':
            case 'decimal':
                $extra_arr['max'] = 'max_digits:2147483647';
                $extra_arr['regex'] = 'regex:/^\d+([.]\d+)?$/';
                break;

            // float or decimal (can below zero)
            case 'float_signed':
            case 'decimal_signed':
                $extra_arr['max'] = 'max_digits:2147483647';
                $extra_arr['regex'] = 'regex:/^[-]?\d+([.]\d+)?$/';
                break;

            // float or decimal
            // allows both comma and dot (must replace before saving)
            case 'eu_float':
            case 'eu_decimal':
                $extra_arr['max'] = 'max_digits:2147483647';
                $extra_arr['regex'] = 'regex:/^\d+([.,]\d+)?$/';
                break;

            // two decimals
            case 'price':
            case 'double':
                $extra_arr['max'] = 'max_digits:2147483647';
                $extra_arr['regex'] = 'regex:/^\d*([.]\d{1,2})?$/';
                break;

            // two decimals
            case 'eu_price':
            case 'eu_double':
                $extra_arr['max'] = 'max_digits:2147483647';
                $extra_arr['regex'] = 'regex:/^\d*([.,]\d{1,2})?$/';
                break;

            // boolean
            case 'boolean':
            case 'bool':
                $extra_arr['boolean'] = 'boolean';
                break;

            // email
            case 'email':
                $extra_arr['email'] = 'email';
                $extra_arr['max'] = 'max:255';
                break;

            // image
            case 'image':
                // size in kb
                $extra_arr['image'] = 'image';
                $extra_arr['max'] = 'max:5000';
                break;

            // file
            case 'file':
            case 'upload':
                // size in kb
                $extra_arr['file'] = 'file';
                $extra_arr['max'] = 'max:5000';
                break;
                
            // date
            case 'date':
                $extra_arr['array'] = 'date';
                break;

            default:
                break;
        }

        // merge with already given array
        $validation_arr = array_merge($extra_arr ?? [], $validation_arr);

        return $validation_arr;
    }

    //
    if (!function_exists('sometimes')) {
        function sometimes($type, $other_validation = [])
        {
            $other_validation = is_array($other_validation) ? $other_validation : [$other_validation];

            return validate($type, array_merge(['sometimes'], $other_validation));
        }
    }

    //
    if (!function_exists('required')) {
        function required($type, $other_validation = [])
        {
            $other_validation = is_array($other_validation) ? $other_validation : [$other_validation];

            return validate($type, array_merge(['required'], $other_validation));
        }
    }

    //
    if (!function_exists('nullable')) {
        function nullable($type, $other_validation = [])
        {
            $other_validation = is_array($other_validation) ? $other_validation : [$other_validation];

            return validate($type, array_merge(['nullable'], $other_validation));
        }
    }

    // version 1
    if (!function_exists('print_validation')) {
        function print_validation($validation_arr) {

            //
            $html = '';

            $validation_html = '';
            foreach ($validation_arr as $key => $validation) {

                //
                $validate_part_html = implode('|', $validation);

                //
                $validation_html .= "'".$key ."' => '". $validate_part_html ."'\r\n";
            }

            //
            $html .= '
                // data validate
                $data = $request->validate([
                    '. $validation_html .'
                ]);
            ';

            var_dump($html); exit;
        }
    }
}
