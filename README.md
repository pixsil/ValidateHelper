# Validate helper Laravel

## What is it?

This is a Laravel helper functions that allows easily to add some default validation for the type of database field next to your own validation. For example a varchar field get automatically an max:255 validation applied. This is a great helper for reducing database errors.

This helper works on all of the following field or database types:
input, varchar, string, password, text, textarea, array, checkbox, float, integer, number, int, boolean, bool, email, image, file, upload

## Donate

Find this project useful? You can support me on Patreon

https://www.patreon.com/pixsil

## Installation

For a quick install, run this from your project root:
```bash
mkdir app/Http/Helpers
wget -O app/Http/Helpers/ValidateHelper.php https://raw.githubusercontent.com/pixsil/ValidateHelper/main/ValidateHelper.php
```

In your general helper file
```php
include('Helpers/ValidateHelper.php');
```

## Usage
This helper functions add the the validate() function to your project. The validate() function allows two parameters. The first one is the type the value or the database type and the second is your own validation that you like to apply.

**Possible values:**
input, varchar, string, password, text, textarea, array, checkbox, integer, number, int, boolean, bool, email, image, file, upload

All of the following name conventions are allowed. By default all the fields are required, you can override this by adding 'sometimes' or 'nullable' to the extra validation array.

Best practice is using the shorthand notations sometimes() and required():
```php
$data = $request->validate([
    'description' => required('string'),
    'price' => sometimes('integer'),
    'amount' => sometimes('integer'),
    'size' => sometimes('integer'),
    'additional_information' => required('text'),
]);

```

 Or you can use the validate function if you dont want to apply sometimes or required. This can be done with the function as follows:

```php
// without additional requirements
$data = $request->validate([
    'description' => validate('string'),
    'price' => validate('integer'),
    'amount' => validate('integer'),
    'size' => validate('integer'),
    'additional_information' => validate('text'),
]);

// with additional requirements
$data = $request->validate([
    'email' => validate('email', 'min:21|max:222'),  // required
    'phone' => validate('string', ['min:21','max:222']), // required
    'company_name' => validate('string', 'sometimes'), // sometimes
    'amount_users' => validate('string', 'sometimes|min:21']), // sometimes
    'amount_users' => validate('string', ['sometimes', 'min:21']), // sometimes
]);
```

**Apply custom validate classes**

Use the validate function with custom validate classes

 ```php
$data = $request->validate([
    'relation_arr' => array_merge(validate('array'), [new PivotTableArray])
]);
```

**How to do a required_if**

For a required if you can use nullable() or add nullable to the other validation array. It basiclly says that all values are allowed only not when an other field got a specific condition.

## Debug

I you would like to print the validation that is rendered you can use the print_validation() helper. Its all in there and ready to use!

```php
print_validation([
  'first_name'=>'min:22|max:25',
  'last_name'=>'min:12|max:15',
])

```
