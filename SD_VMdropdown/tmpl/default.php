<?php
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
	defined('_JEXEC') or die();
	$class='vmcustom-SD_VMdropdown';
?>
    <select class="<?php echo $class ?>" name="customPlugin[<?php echo $viewData[0]->virtuemart_customfield_id ?>][<?php echo $this->_name?>][custom_value]">
        <?php
            $data = explode(';',trim($viewData[0]->custom_value));
            foreach($data as $value){
                echo '<option>'.$value.'</option>';
            }
        ?>
    </select>