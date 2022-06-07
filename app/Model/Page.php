<?php

class Page extends AppModel {

    public $name = 'Page';
    public $validate = array(
        /*'name' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Page name can\'t be empty',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Page name already exists, please use different one'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Page name must be no larger than 100 characters'
            )
        ),*/
        'slug' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Page Name',//Slug error will be display in front of page name
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Page Name Already Exists'//Slug error will be display in front of page name
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 100),
                'message' => 'Page Name Should Be Maximum 100 Characters'
            )
        )
    );

    public $hasMany = array(
        'PageLocale' => array(
            'className' => 'PageLocale',
            'foreignKey' => 'page_id'
        )
    );

}

?>