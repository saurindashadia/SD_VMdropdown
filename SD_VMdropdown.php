<?php
defined('_JEXEC') or die( 'Direct Access to is not allowed.' ) ;
/**
 * @author Saurin Dashadia
 * @url http://saur.in
 * @package VirtueMart
 * @subpackage payment
 * @copyright Copyright (C) 20014 Saurin Dashadia - All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 * This plugin is developed by extending VMCustom - textinput
 */

if (!class_exists('vmCustomPlugin')) require(JPATH_VM_PLUGINS . DS . 'vmcustomplugin.php');

class plgVmCustomSD_VMdropdown extends vmCustomPlugin {

	function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
	}

	// get product param for this plugin on edit
	function plgVmOnProductEdit($field, $product_id, &$row,&$retValue) {
		if ($field->custom_element != $this->_name) return '';
		$this->parseCustomParams($field);
        $retValue .='<input type="text" value="'.$field->custom_value.'" name="custom_param['.$row.'][custom_value]" id="custom_param['.$row.'][custom_value]" class="inputbox dropdown">';
		$row++;
		return true ;
	}

	/**
	 * @ idx plugin index
	 * @see components/com_virtuemart/helpers/vmCustomPlugin::onDisplayProductFE()
	 * eg. name="customPlugin['.$idx.'][comment] save the comment in the cart & order
	 */
	function plgVmOnDisplayProductVariantFE($field,&$idx,&$group) {
		// default return if it's not this plugin
		if ($field->custom_element != $this->_name) return '';

		$this->getCustomParams($field);
		$group->display .= $this->renderByLayout('default',array($field,&$idx,&$group ) );
		return true;
    }

	/**
	 * @see components/com_virtuemart/helpers/vmCustomPlugin::plgVmOnViewCartModule()
	 */
	function plgVmOnViewCartModule( $product,$row,&$html) {
		return $this->plgVmOnViewCart($product,$row,$html);
    }

	/**
	 * @see components/com_virtuemart/helpers/vmCustomPlugin::plgVmOnViewCart()
	 */
	function plgVmOnViewCart($product,$row,&$html) {
		if (empty($product->productCustom->custom_element) or $product->productCustom->custom_element != $this->_name) return '';
		if (!$plgParam = $this->GetPluginInCart($product)) return '' ;

		foreach($plgParam as $k => $item){
			if(!empty($item['custom_value']) && $product->productCustom->virtuemart_customfield_id==$k){
                $html .='<span>'.JText::_($product->productCustom->custom_title).' '.$item['custom_value'].'</span>';
			}
		 }
		return true;
    }

	function plgVmDisplayInOrderBE($item, $row, &$html) {
		if(!empty($productCustom))
			$item->productCustom = $productCustom;

		if (empty($item->productCustom->custom_element) or $item->productCustom->custom_element != $this->_name) return '';
		$this->plgVmOnViewCart($item,$row,$html); //same render as cart
    }

	function plgVmDisplayInOrderFE($item, $row, &$html) {
		if (empty($item->productCustom->custom_element) or $item->productCustom->custom_element != $this->_name) return '';
		$this->plgVmOnViewCart($item,$row,$html); //same render as cart
    }

	function plgVmDeclarePluginParamsCustom($psType,$name,$id, &$data){
		return $this->declarePluginParams('custom', $name, $id, $data);
	}

	function plgVmSetOnTablePluginParamsCustom($name, $id, &$table){
		return $this->setOnTablePluginParams($name, $id, $table);
	}

	function plgVmOnDisplayEdit($virtuemart_custom_id,&$customPlugin){
		return $this->onDisplayEditBECustom($virtuemart_custom_id,$customPlugin);
	}

	public function plgVmCalculateCustomVariant($product, &$productCustomsPrice,$selected){
		if ($productCustomsPrice->custom_element !==$this->_name) return ;
		$customVariant = $this->getCustomVariant($product, $productCustomsPrice,$selected);

		if (!empty($productCustomsPrice->custom_price)) {
			if (!empty($customVariant['comment'])) {
                $charcount =  ($productCustomsPrice->custom_price_by_letter ==1) ? strlen ($customVariant['comment']) : 1.0 ;
				$productCustomsPrice->custom_price = $charcount * $productCustomsPrice->custom_price ;
			} else {
				$productCustomsPrice->custom_price = 0.0;
			}
		}
		return true;
	}

	public function plgVmDisplayInOrderCustom(&$html,$item, $param,$productCustom, $row ,$view='FE'){
		$this->plgVmDisplayInOrderCustom($html,$item, $param,$productCustom, $row ,$view);
	}

    function plgVmOnSelfCallFE($type,$name,&$render) {
        $render->html = '';
    }

    public function plgVmOnStoreInstallPluginTable($psType) {}

	public function plgVmCreateOrderLinesCustom(&$html,$item,$productCustom, $row ){}

    //function plgVmOnDisplayProductFE( $product, &$idx,&$group){}
}