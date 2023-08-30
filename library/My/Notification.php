<?php
class My_Notification extends Zend_Controller_Plugin_Abstract
{
    const TITLE      = 1;
    const TEAM       = 2;
    const DEPARTMENT = 3;
    const ALL_STAFF  = 4;    

    public static function send ($title, $content, $staff_id, $pop_up = 0) {
        $userStorage         = Zend_Auth::getInstance()->getStorage()->read();
        $QNotification       = new Application_Model_Notification();
        $QNotificationAccess = new Application_Model_NotificationAccess();
        $current_time = date('Y-m-d H:i:s');

        $id = $QNotification->insert (
            [
                'title' => trim($title),
                'content' => trim($content),
                'created_at' => $current_time,
                'created_by' => $userStorage->id,
                'pop_up' => $pop_up,
                'status' => 1
            ]
        );

        $QNotificationAccess->insert(
            [
                'user_id' => $staff_id,
                'notification_id' => $id,
                'created_date' => $current_time
            ]
        );
    }
}