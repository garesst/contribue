<?php

namespace contribue\Controller;

include_once('Commons/DB.php');
use Commons\DB;
use PDO;

class tenant_controller
{

    function checkActiveSubscription($workspace){
        $db = new DB();
        try {
            $db->connect();
            $sql = "SELECT s.* FROM tenant t INNER JOIN subscriptions s ON t.id_tenant = s.tenant_id WHERE t.workspace = :workspace AND s.status_sub = 'ACT' AND s.trial_end_date IS NULL;";
            $params = [':workspace' => $workspace];
            $sub = $db->query($sql, $params);
            return $sub->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la busqueda de la subscripcion");
        } finally {
            $db->disconnect();
        }
    }

}