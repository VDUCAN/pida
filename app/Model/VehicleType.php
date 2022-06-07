<?php

class VehicleType extends AppModel {

    public $name = 'VehicleType';
    public $validate = array(
        'category_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Category',
                'allowEmpty' => false
            )
        ),
        'vehicle_type_image' => array(
            'upload-file' => array(
                'rule' => array('validate_file', 'vehicle_type_image'),
                'message' => 'Only jpg, jpeg, png, gif Files Allowed'
            )
        )
    );

    public $belongsTo = array(
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id'
        )
    );

    public $hasMany = array(
        'VehicleTypeLocale' => array(
            'className' => 'VehicleTypeLocale',
            'foreignKey' => 'vehicle_type_id'
        )
    );

    public function validate_file($file_data, $field)
    {
        $file_data = array_shift($file_data);

        if (0 === $file_data['error']) {

            $allowed_extensions = array();
            if('vehicle_type_image' == $field){
                $allowed_extensions = array('gif', 'jpg', 'jpeg', 'png');
            }

            $file_name = $file_data['name'];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            if(!in_array($file_ext, $allowed_extensions)) {
                return false;
            }
        }
        return true;

    }

}

?>