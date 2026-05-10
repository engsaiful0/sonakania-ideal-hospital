<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_engine
{
    /**
     * Evaluate lab test result based on parameter rules
     *
     * @param mixed $value
     * @param object $parameter (expects min_value, max_value, input_type)
     * @return string|null
     */
    public function evaluate($value, $parameter)
    {
        $type = $parameter->input_type;

        switch ($type) {

            case 'numeric':
                if ($value === null || $value === '') {
                    return null;
                }

                $value = floatval($value);
                $min = isset($parameter->min_value) ? floatval($parameter->min_value) : null;
                $max = isset($parameter->max_value) ? floatval($parameter->max_value) : null;

                if ($min !== null && $value < $min) {
                    return 'Low';
                }

                if ($max !== null && $value > $max) {
                    return 'High';
                }

                return 'Normal';


            case 'boolean':
                // Accept: 1, true, "true", "positive", "yes"
                $positiveValues = [1, true, '1', 'true', 'yes', 'positive', 'Positive'];

                if (in_array($value, $positiveValues, true)) {
                    return 'Positive';
                }

                return 'Negative';


            case 'text':
                // No evaluation for text-based parameters
                return null;


            default:
                return null;
        }
    }
}