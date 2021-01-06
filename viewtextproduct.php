<?php
/*
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2015 PrestaShop SA

 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class ViewTextProduct extends Module implements WidgetInterface
{

    protected $config_form = false;

    public function __construct()
    {

        $this->name = 'viewtextproduct';
        $this->author = 'Anthony';
        $this->version = '1.0.0';
        $this->controllers = array('default');
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->displayName = $this->l('Mostrar texto en un producto');
        $this->description = $this->l('Tarea número 5 de Prestashop para webImpacto');
        $this->confirmUninstall = $this->l('¿Quieres desinstalar este modulo?');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        parent::__construct();
    }

    public function install()
    {
        include(dirname(__FILE__).'/sql/install.php');

            return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle') && 
            $this->registerHook('displayProductAdditionalInfo');
    }

    public function uninstall()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
        return parent::uninstall();
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        if ($hookName == 'displayAdminProductsMainStepLeftColumnMiddle') { 
            $template = '/views/templates/back.tpl';
        } elseif ($hookName == 'displayProductAdditionalInfo') { 
            $template = '/views/templates/front.tpl';
        }
        return $this->fetch('module:viewtextproduct'.$template);
    }
     
    public function getWidgetVariables($hookName, array $configuration)
    {
        if (isset($configuration['product']->id_product) && $configuration['product']->id_product != null) {
            $product = new Product($configuration['product']->id_product);
        } elseif (isset($configuration['id_product']) && $configuration['id_product'] != null) {
            $product = new Product($configuration['id_product']);
        }
        $this->context->smarty->assign(array('viewproduct_text' => $product->viewproduct_text));
    }
    
}