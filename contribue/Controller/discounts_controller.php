<?php

namespace contribue\Controller;
include_once('Commons/DB.php');
include_once('Controller/tenant_controller.php');

use Commons\DB;
use PDO;

class discounts_controller
{

    public function getAvailableDiscount($cod_discount) {
        $db = new DB();
        try {
            $sql = "SELECT id as id_discounts ,discount_type, value as mount FROM discounts WHERE code = :cod_discount AND current_date BETWEEN start_date AND end_date;";
            $db->connect();
            $plans = $db->query($sql, [":cod_discount" => $cod_discount]);
            return $plans->fetch(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la busqueda de los descuentos");
        } finally {
            $db->disconnect();
        }
    }

}