<?php

namespace contribue\Controller;

include_once('Commons/DB.php');
use Commons\DB;
use Exception;
use PDO;

class tenant_controller
{

    function checkActiveSubscription($workspace){
        $db = new DB();
        try {
            $db->connect();
            $sql = "SELECT s.* FROM tenant t INNER JOIN subscriptions s ON t.id_tenant = s.tenant_id WHERE t.workspace = :workspace AND s.status_sub = 'ACT';";
            $params = [':workspace' => $workspace];
            $sub = $db->query($sql, $params);
            return $sub->fetchAll(PDO::FETCH_ASSOC);
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la busqueda de la subscripcion");
        } finally {
            $db->disconnect();
        }
    }

    function checkTrialSubscription($workspace){
        $db = new DB();
        try {
            $db->connect();
            $sql = "SELECT COUNT(*) FROM tenant t INNER JOIN subscriptions s ON t.id_tenant = s.tenant_id WHERE t.workspace = :workspace AND s.trial_end_date IS NOT NULL;";
            $params = [':workspace' => $workspace];
            $sub = $db->query($sql, $params);
            return $sub->fetchColumn();
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la busqueda de la subscripcion");
        } finally {
            $db->disconnect();
        }
    }

    function getTenantIdByWorkspace($workspace) {
        $db = new DB();
        try {
            $db->connect();
            $sql = "SELECT id_tenant FROM tenant WHERE workspace = :workspace";
            $params = [':workspace' => $workspace];
            $sub = $db->query($sql, $params);

            return $sub->fetch(PDO::FETCH_ASSOC)['id_tenant'];
        }catch (Exception $e){
            throw new Exception("Ocurri un error obteniendo el tenant".$e);
        } finally {
            $db->disconnect();
        }
    }

}