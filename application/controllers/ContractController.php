<?php

class ContractController extends My_Controller_Action {
    public function printAction() {

    }

    public function saveAssignLabelAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        echo '<link href="/css/bootstrap.min.css" rel="stylesheet">';
        $db = Zend_Registry::get('db');
        $db->beginTransaction();
        try {
            $userStorage  = Zend_Auth::getInstance()->getStorage()->read();
            $variable     = $this->getRequest()->getParam('variable', []);
            $QAssignLabel = new Application_Model_AssignLabel();
            // bỏ mấy phần tử empty đi
            $variable_filter = array_filter($variable, fn($value) => !is_null($value) && $value !== '');
            if (count($variable_filter) <> count(array_unique($variable_filter))) throw new Exception ("Các biến số trong danh sách phải khác nhau");    
            foreach ($variable_filter as $key => $value) { 
                $QAssignLabel->update (
                    [
                        'variable'  => $value,
                        'assign_by' => $userStorage->id,
                        'assign_at' => date('Y-m-d H:i:s')
                    ], [
                        $QAssignLabel->getAdapter()->quoteInto ('id = ?', $key)
                    ]
                );
            }
            $cache = Zend_Registry::get('cache');
            $cache->remove('assign_label_cache');
            $db->commit();
            echo '<div class="alert alert-success">Success!</div>';
        }catch (Exception $e) {
            $db->rollBack();
            echo '<div class="alert alert-error">Failed - ' . $e->getMessage() . '</div>';
        }
        echo '<script>window.parent.scroll2Top();</script>';
        echo '<script>window.parent.unblockUI();</script>';
        echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
        echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
        echo '<script>window.parent.document.location.reload();</script>';
    }

    public function doAssignLabelAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $db = Zend_Registry::get('db');
        $db->beginTransaction();
        try {
            $userStorage  = Zend_Auth::getInstance()->getStorage()->read();
            $id           = $this->getRequest()->getParam('id', '');
            $input        = $this->getRequest()->getParam('input', '');
            $QAssignLabel = new Application_Model_AssignLabel();
        
            $where       = [];
            $where[]     = $QAssignLabel->getAdapter()->quoteInto ('id <> ?', $id);
            $where[]     = $QAssignLabel->getAdapter()->quoteInto ('input = ?', trim($input));
            $exist_input = $QAssignLabel->fetchRow ($where);
            if($exist_input) throw new Exception ("The input is already exist");
        
            $QAssignLabel->update (
                [
                    'variable'  => $input,
                    'assign_at' => date('Y-m-d H:i:s'),
                    'assign_by' => $userStorage->id
                ],
                [
                    $QAssignLabel->getAdapter()->quoteInto('id = ?', $id)   
                ]
            );
            $cache = Zend_Registry::get('cache');
            $cache->remove('assign_label_cache');
            $db->commit();
            echo json_encode(['status' => 1]);
            return;
        }catch (Exception $e) {
            $db->rollBack();
            echo json_encode([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
            return;
        }
    }

    public function assignLabelAction() {
        $variable     = $this->getRequest()->getParam('variable', null);
        $title        = $this->getRequest()->getParam('title', null);
        $status       = $this->getRequest()->getParam('status', null);
        $export       = $this->getRequest()->getParam('export', null);
        $QAssignLabel = new Application_Model_AssignLabel();
        
        $where   = [];
        $where[] = $QAssignLabel->getAdapter()->quoteInto('1 = ?', 1);
        if ($variable) $where[] = $QAssignLabel->getAdapter()->quoteInto('variable LIKE ?', '%' . $variable . '%');
        if ($title) $where[] = $QAssignLabel->getAdapter()->quoteInto('name LIKE ?', '%' . $title . '%');
        if ($status) {
            if($status == -1) $where[] = $QAssignLabel->getAdapter()->quoteInto('assign_at IS NULL', true);
            elseif ($status == 1) $where[] = $QAssignLabel->getAdapter()->quoteInto('assign_at IS NOT NULL', true);
        }
        $data = $QAssignLabel->fetchAll($where)->toArray();
        if($export) {
            ini_set("memory_limit", -1);
            ini_set("display_error", 0);
            error_reporting(~E_ALL);
        
            $heads = array(
                '#',
                'Giá trị',
                'Biến gán'
            );
        
            $i = 1;
            $contents = [];
            foreach($data as $item){
                $data_item = [
                    $i++,
                    $item['name'],
                    $item['variable']
                ];
                $contents[] = $data_item;
            }
            $filename = 'Assign_Label_'.date('d-m-Y') . '.xlsx';
            My_BoxSpout::excelBuilder($heads, $contents, $filename);
        }
        
        $params = [
            'variable' => $variable,
            'title'    => $title,
            'status'   => $status
        ];
        $this->view->params       = $params;
        $this->view->assign_label = $data;
    }
}