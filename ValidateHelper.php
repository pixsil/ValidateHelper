<?php

// version 10.1

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
        if (in_array('nullable', $validation_arr)) {
            $extra_arr = ['nullable' => 'nullable'];
        } elseif (in_array('sometimes', $validation_arr)) {
            $extra_arr = ['sometimes' => 'sometimes'];
        } else {
            $extra_arr = ['required' => 'required'];
        }

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
                $extra_arr['max'] = 'max:500';
                break;

            // checkbox or array
            case 'array':
            case 'checkbox':
                $extra_arr['array'] = 'array';
                break;

            // textarea
            case 'integer':
            case 'number':
            case 'int':
                $extra_arr['integer'] = 'integer';
                $extra_arr['max'] = 'max:2147483647';
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

            // email
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

            default:
                break;
        }

        // merge with already given array
        $validation_arr = array_merge($extra_arr, $validation_arr);

        return $validation_arr;
    }

    //
    if (!function_exists('sometimes')) {
        function sometimes($type, $other_validation = [])
        {
            return validate($type, array_merge(['sometimes'], $other_validation));
        }
    }

    //
    if (!function_exists('required')) {
        function required($type, $other_validation = [])
        {
            return validate($type, $other_validation);
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
