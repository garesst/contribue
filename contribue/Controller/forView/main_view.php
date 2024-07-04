<?php

namespace contribue\Controller\forView;

include_once('Controller/subscription_controller.php');

use contribue\Controller\subscription_controller;

use DateTime;

class main_view
{

    public $planes;
    public array $plansByPeriod = [];

    public function __construct()
    {
    }

    function getPlans(){
        $plans = new subscription_controller();
        $this->planes = $plans->getAvailablePlans();
        foreach ($this->planes as $plan) {
            $planId = $plan['plan_id'];
            $period = $plan['period'];

            if (!isset($this->plansByPeriod[$planId])) {
                $this->plansByPeriod[$planId] = [
                    'plan_name' => $plan['plan_name'],
                    'plan_description' => $plan['plan_description'],
                    'prices' => [],
                    'features' => $plan['features']
                ];
            }

            $this->plansByPeriod[$planId]['prices'][$period] = $plan['price'];
            foreach ($this->plansByPeriod as $planId => &$plans) {
                if (is_string($plans['features'])) {
                    $this->plansByPeriod[$planId]['features'] = $this->hstore_to_array($plans['features']);
                } else {
                    error_log("Warning: features is not a string. Actual type: " . gettype($plans['features']));
                }
            }

        }
        return $this->planes;
    }

    public function hstore_to_array($hstoreString) {
        $result = [];
        $pairs = explode('", "', trim($hstoreString, '"')); // Divide en las comillas dobles
        foreach ($pairs as $pair) {
            list($key, $value) = explode('"=>"', $pair, 2); // Divide en '"=>"'
            $key = trim($key, ' "');
            $value = trim($value, ' "');
            $result[$key] = $value;
        }
        return $result;
    }


}