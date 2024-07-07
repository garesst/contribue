<?php

namespace contribue\Controller;
include_once('Commons/DB.php');
include_once('Controller/tenant_controller.php');
include_once('Controller/discounts_controller.php');

use Commons\DB;
use DateTime;
use Exception;
use PDO;

class subscription_controller
{

    private $db;

    public function __construct()
    {
    }

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

    function checkTrialSubscription($workspace){
        try {
            $tenantController = new tenant_controller();
            return $tenantController->checkTrialSubscription($workspace);
        }catch (Exception $e){
            throw new Exception("Ocurri un error en la verificacion de la subscripcion");
        }
    }

    public function redeemTrialTime($workspace, $payment_plan_id, $period, $amount,): void
    {
        try{
            $checkValid = $this->checkTrialSubscription($workspace);
            if($checkValid===0){
                $this->sellSubscription($workspace, $payment_plan_id, $period, $amount, null, 'TRIAL');
            }else{
                throw new Exception("No se puede tener mas de un periodo de prueba");
            }
        }catch (Exception $e){
            throw new Exception("Ocurri un error al crear la suscripcion");
        }
    }
    private function sellSubscription($workspace, $payment_plan_id, $period, $amount, $startDate, $cod_discount = null): void
    {
        try {
            $tenantController = new tenant_controller();
            $tenant_id = $tenantController->getTenantIdByWorkspace($workspace);
            $discountController = new discounts_controller();
            if($cod_discount!==null){
                $discount_id = $discountController->getAvailableDiscount($cod_discount);
                $amount = $discount_id['discount_type'] === 'PER' ? $amount * (1 - ($discount_id['mount']/100)) : (($amount - $discount_id['mount']) >= 0 ? $amount - $discount_id['mount'] : 0);

            }
            $this->db = new DB();
            $this->db->connect();
            $this->db->conn->beginTransaction();

            if (!$tenant_id) {
                throw new Exception("No se encontró un tenant con el workspace proporcionado.");
            }

            $subscription_id = $this->insertSubscription($tenant_id, $payment_plan_id, $period, $cod_discount, $startDate);

            $this->insertPayment($subscription_id, $amount, ($cod_discount!==null?$discount_id['id_discounts']:null));

            $this->db->conn->commit();

        } catch (Exception $e) {
            $this->db->conn->rollBack();
            throw new Exception("Error al crear la suscripción y el pago: " . $e->getMessage());
        } finally {
            $this->db->disconnect();
        }
    }

    private function insertSubscription($tenant_id, $payment_plan_id, $period, $cod_discount, $startDate) {
        $start_date = $startDate===null?new DateTime():$startDate;
        $end_date = ($start_date)->modify($period === 'ANUAL' ? '+1 year' : '+1 month');


        $params = [
            'tenant_id' => $tenant_id,
            'payment_plan_id' => $payment_plan_id,
            'period' => $period,
            'start_date' => $start_date->format('Y-m-d H:i:s'),
            'end_date' => $end_date->format('Y-m-d H:i:s'),
            'status_sub' => 'ACT',
            'trial_end_date' => $cod_discount==='TRIAL'?$end_date->format('Y-m-d H:i:s'):null
        ];

        return $this->db->insertReturn('subscriptions', $params, 'id');
    }

    private function insertPayment($subscription_id, $amount, $discount_id = null) {
        $params = [
            'subscription_id' => $subscription_id,
            'amount' => $amount,
            'status_payment' => 'ACT',
            'id_discounts' => $discount_id
        ];
        $this->db->insert('payments', $params);
    }

}