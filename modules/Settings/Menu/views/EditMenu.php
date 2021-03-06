<?php

/**
 * Settings menu EditMenu view class
 * @package YetiForce.View
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 */
class Settings_Menu_EditMenu_View extends Settings_Vtiger_IndexAjax_View
{

	public function process(\App\Request $request)
	{
		$qualifiedModuleName = $request->getModule(false);
		$id = $request->get('id');
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_MODEL', Settings_Menu_Module_Model::getInstance());
		$viewer->assign('RECORD', Settings_Menu_Record_Model::getInstanceById($id));
		$viewer->assign('ICONS_LABEL', Settings_Menu_Record_Model::getIcons());
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('ID', $id);
		$viewer->view('EditMenu.tpl', $qualifiedModuleName);
	}
}
