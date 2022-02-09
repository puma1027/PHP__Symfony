<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class SC_SiteView extends SC_View_Ex
{
    public function __construct($setPrevURL = true)
    {
        parent::__construct();

        if ($setPrevURL) {
            $this->setPrevURL();
        }
    }

    public function init()
    {
        parent::init();

        $this->_smarty->template_dir = realpath(TEMPLATE_REALDIR);
        $this->_smarty->compile_dir = realpath(COMPILE_REALDIR);

        $this->assignTemplatePath(DEVICE_TYPE_PC);
    }

    public function setPrevURL()
    {
            $objCartSess = new SC_CartSession_Ex();
            $objCartSess->setPrevURL($_SERVER['REQUEST_URI']);
    }

	 // �e���v���[�g�̏������ʂ�\��
    function display($template, $no_error = false) {
        if(!$no_error) {
            global $GLOBAL_ERR;
            if(!defined('OUTPUT_ERR')) {
                // GLOBAL_ERR �����蓖��
                $this->assign("GLOBAL_ERR", $GLOBAL_ERR);
                define('OUTPUT_ERR','ON');
            }
        }

        $this->_smarty->display($template);
        if(ADMIN_MODE == '1') {
            $time_end = time();
            $time = $time_end - $this->time_start;
            print("��������:" . $time . "�b");
        }
    }

    function assignobj($obj) {
        $data = get_object_vars($obj);

        foreach ($data as $key => $value){
            $this->_smarty->assign($key, $value);
        }
    }
}
