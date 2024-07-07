<?php
include_once('Controller/forView/main_view.php');

use contribue\Controller\forView\main_view;

$controller = new main_view();

if (!$session->isActive && !empty($controller->getPlans())): ?>
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-screen-xl px-4 mx-auto py-12 lg:py-2 lg:px-6 sm:py-24">
            <div class="max-w-screen-md mx-auto mb-8 text-center lg:mb-12">
                <h2 class="mb-4 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Diseñado para
                    hacer
                    negocios, soluciones a medida para cada etapa de tu crecimiento</h2>
                <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">Aqui nos centramos en
                    herramientas
                    poderosas para profesionales, pequeñas empresas y grandes corporaciones, escoge el plan que mejor se
                    adapte a tus necesidades y lleva tu gestión al siguiente nivel</p>
            </div>
            <div class="space-y-8 lg:grid lg:grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">

                <?php foreach ($controller->plansByPeriod as $plan): ?>
                    <div
                            class="flex flex-col max-w-lg p-6 mx-auto text-center text-gray-900 bg-white border border-gray-100 rounded-lg shadow dark:border-gray-600 xl:p-8 dark:bg-gray-800 dark:text-white">
                        <h3 class="mb-4 text-2xl font-semibold"><?php echo htmlspecialchars($plan['plan_name']); ?></h3>
                        <p class="font-light text-gray-500 sm:text-lg dark:text-gray-400">
                            <?php echo htmlspecialchars($plan['plan_description']); ?>
                        </p>
                        <div class="flex items-baseline justify-center mt-8">
                            <span class="mr-2 text-5xl font-extrabold">
                                <?php if (isset($plan['prices']['MES'])): ?>
                                    $<?php echo htmlspecialchars($plan['prices']['MES']); ?>
                                <?php endif; ?>
                            </span>
                            <span class="text-gray-500 dark:text-gray-400">USD/mes</span><br/>
                        </div>
                        <div class="flex items-baseline justify-center mb-8">
                            <span class="text-gray-500 dark:text-gray-400">
                                <?php if (isset($plan['prices']['MES'])): ?>
                                    $<b><?php echo htmlspecialchars($plan['prices']['ANUAL']); ?></b>
                                <?php endif; ?>
                                USD/año
                            </span>
                        </div>

                        <ul role="list" class="mb-8 space-y-4 text-left">
                            <?php foreach ($plan['features'] as $feature): ?>
                            <li class="flex items-center space-x-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-500 dark:text-green-400"
                                     fill="currentColor"
                                     viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                <span><?php echo $feature; ?></span>
                            </li>
                            <?php endforeach; ?>
                            <li>
                                <sup>*aplica costos adicionales</sup><br/><sup>**GB adicional aplica costos</sup>
                            </li>
                        </ul>
                        <?php if ($session->isNew && !$session->isActive): ?>
                            <button data-sbm="true" data-method="POST" data-action="api/subscription/0.0.1/subscriptionTrialService" data-object="payment_plan_id='<?php echo $plan['plan_id']; ?>';amount='<?php echo htmlspecialchars($plan['prices']['MES']); ?>'"
                                    class="mt-auto  text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white  dark:focus:ring-purple-900 ">
                                Obtener mes de prueba
                            </button>
                        <?php else: ?>
                            <button data-sbm="true" data-method="POST" data-action="url" data-object="valoruno='';valordos='';valortres=45"
                               class="mt-auto  text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-white  dark:focus:ring-purple-900 ">
                                Comprar
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <ul>
    </ul>
<?php else: ?>
    <p>Aqui iria el dashboard si es que lo hago</p>
<?php endif; ?>

