<?php

namespace contribue\Controller;
include_once('Commons/DB.php');
include_once('Controller/tenant_controller.php');

use Commons\DB;
use PDO;

class subscription_controller
{
    public function getAvailablePlans() {
        $db = new DB();
        try {
            $sql = "SELECT p.id AS plan_id, p.name AS plan_name, p.description AS plan_description, pp.period, pp.price, p.feature AS features FROM payment_plans p INNER JOIN plan_prices pp ON p.id = pp.payment_plan_id;";
            $db->connect();
            $plans = $db->query($sql, []);
            return $plans->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la busqueda de la planes");
        } finally {
            $db->disconnect();
        }
    }

    function checkActiveSubscription($workspace){
        try {
            $tenantController = new tenant_controller();
            return $tenantController->checkActiveSubscription($workspace);
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la verificacion de la subscripcion");
        }
    }

}