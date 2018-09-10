<?php

namespace Drupal\civihr_employee_portal\Mail;

use Drupal\civihr_employee_portal\Service\ContactService;

class EmergencyContactDeletionNotificationMail extends AbstractDrupalSystemMail {

  /**
   * @inheritdoc
   */
  public function getVariables($message) {
    $contactId = \CRM_Core_Session::singleton()->getLoggedInContactID();
    $contact = civicrm_api3('Contact', 'getsingle', ['id' => $contactId]);
    $contactEmail = ContactService::getContactWorkEmail($contactId);
    $profileLink = ContactService::getLinkToContactProfile($contactId);
    $emergencyContact = $message['params']['emergencyContact'];

    return [
      'workEmail' => $contactEmail,
      'profileLink' => $profileLink,
      'displayName' => $contact['display_name'],
      'submissionDate' => date('Y-m-d H:i'),
      'emergencyContactName' => $emergencyContact['Name'],
      'emergencyContactType' => $this->getEmergencyContactType($message),
    ];
  }

  /**
   * @inheritdoc
   */
  public function getTemplateName() {
    return 'emergency_contact_deletion.tpl';
  }

  /**
   * @inheritdoc
   */
  public function getSubject($message) {
    $format = 'CiviHR Self Service Data Submission - %s Deleted';

    return sprintf($format, ucwords($this->getEmergencyContactType($message)));
  }

  /**
   * Gets the emergency contact from the message and determines if it is a
   * 'dependant' or 'emergency contact'
   *
   * @param array $message
   *
   * @return string
   */
  private function getEmergencyContactType($message) {
    $emergencyContact = $message['params']['emergencyContact'];
    $isDependant = $emergencyContact['Dependant_s_'] === 'yes';

    return $isDependant ? 'dependant' : 'emergency contact';
  }

}
