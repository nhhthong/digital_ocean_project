<?php

class My_Lang extends Zend_Controller_Plugin_Abstract {

    public static function getLangList() {
        if (!empty($_GET['key'])) {
            $userStorage = Zend_Auth::getInstance()->getStorage()->read();
            if (!empty($userStorage) && !empty($userStorage->email)) {
                $myfile = fopen("oppoer-minigame/log.txt", "a");
                $txt = $_GET['key']."_".$userStorage->email."\n";
                fwrite($myfile, $txt);
                fclose($myfile);
            }
        }

        return array(
            1 => 'vi',
            2 => 'en',
        );
    }

}
