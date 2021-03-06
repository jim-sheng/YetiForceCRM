<?php
/**
 * Mail scanner action bind ServiceContracts
 * @package YetiForce.MailScanner
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

/**
 * Mail scanner action bind ServiceContracts
 */
class OSSMailScanner_BindServiceContracts_ScannerAction
{

	/**
	 * Process
	 * @param OSSMail_Mail_Model $mail
	 * @return array
	 */
	public function process(OSSMail_Mail_Model $mail)
	{
		$mailId = $mail->getMailCrmId();
		$returnIds = [];
		if (!$mailId) {
			return $returnIds;
		}

		$accountNumbers = [];
		$accounts = $mail->getActionResult('Accounts');
		if ($accounts) {
			$keys = array('BindAccounts', 'BindContacts', 'BindLeads', 'BindHelpDesk');
			foreach ($keys as $key) {
				$accountNumbers = array_merge($accountNumbers, $accounts[$key]);
			}
		}

		if ($accountNumbers) {
			$result = (new App\Db\Query())->select(['servicecontractsid'])->from('vtiger_servicecontracts')->innerJoin('vtiger_crmentity', 'vtiger_servicecontracts.servicecontractsid = vtiger_crmentity.crmid')->where(['vtiger_crmentity.deleted' => 0, 'sc_related_to' => $accountNumbers, 'contract_status' => 'In Progress'])->scalar();
			if ($result) {
				$serviceContractsId = $result;
				$status = (new OSSMailView_Relation_Model())->addRelation($mailId, $serviceContractsId, $mail->get('udate_formated'));
				if ($status) {
					$returnIds[] = $serviceContractsId;
				}
			}
		}
		return $returnIds;
	}
}
