<?php

class Vehicle extends AppModel {

    public $name = 'Vehicle';

    public $validate = array(
        'user_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Driver',
                'allowEmpty' => false
            )
        ),
        'vehicle_type' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Vehicle Type',
                'allowEmpty' => false
            )
        ),
        'make_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Vehicle Make',
                'allowEmpty' => false
            )
        ),
        'model_id' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Select Vehicle Model',
                'allowEmpty' => false
            )
        ),
        'plate_no' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Plate Number',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Plate Number Already Exists'
            )
        ),
        'registration_no' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Registration Number',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Registration Number Already Exists'
            )
        ),
        'make_year' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Make Year',
                'allowEmpty' => false
            ),
            'allowNumberOnly' => array(
                'rule' => '|^[0-9]*$|',
                //'rule' => 'numeric',
                'message' => 'Enter Valid Year',
                'allowEmpty' => false
            ),
            'minLength' => array(
                'rule' => array('minLength', 4),
                'message' => 'Enter Valid Year'
            ),
            'maxLength' => array(
                'rule' => array('maxLength', 4),
                'message' => 'Enter Valid Year'
            ),
            'compareYear' => array(
                'rule' => array('compareYear'),
                'message' => 'Enter Valid Year'
            )
        ),
        'insurance_policy_no' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Insurance Policy Number',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule' => array('isUnique'),
                'message' => 'Insurance Policy Number Already Exists'
            )
        ),
        'insurance_expiry_date' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please Enter Insurance Expiry Date',
                'allowEmpty' => false
            )
        ),
        'registration_doc_file' => array(
            'upload-file' => array(
                'rule' => array('validate_file', 'registration_doc_file'),
                'message' => 'Only jpg, jpeg, png, gif Files Allowed'
            )
        ),
        'insurance_policy_doc_file' => array(
            'upload-file' => array(
                'rule' => array('validate_file', 'insurance_policy_doc_file'),
                'message' => 'Only jpg, jpeg, png, gif Files Allowed'
            )
        )
    );

/*    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        ),
        'Make' => array(
            'className' => 'Make',
            'foreignKey' => 'make_id'
        ),
        'Models' => array(
            'className' => 'Models',
            'foreignKey' => 'model_id'
        )
    ); */

    public function validate_file($file_data, $field)
    {
        $file_data = array_shift($file_data);

        if (0 === $file_data['error']) {

            $allowed_extensions = array();
            if(in_array($field, array('registration_doc_file', 'insurance_policy_doc_file'))){
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

    public function compareYear($data){
        $data = array_shift($data);
        $current_year = date('Y');
        return ($data <= $current_year) ? true : false;
    }
    
    public function getDriverVehicles($params=NULL){
	
	$userId = $params['user_id'];
	$langCode = DEFAULT_LANGUAGE;
	
	$this->bindModel(
		array(
		    'hasOne' => array(
			'MakeLocale' => array(
			    'className' => 'MakeLocale',
			    'foreignKey' => false,
			    'conditions' => array("Vehicle.make_id = MakeLocale.make_id",'MakeLocale.lang_code' => $langCode),
			),
			'ModelLocale' => array(
			    'className' => 'ModelLocale',
			    'foreignKey' => false,
			    'conditions' => array("Vehicle.model_id = ModelLocale.model_id",'ModelLocale.lang_code'=>$langCode),
			),
			'VehicleTypeLocale' => array(
			    'className' => 'VehicleTypeLocale',
			    'foreignKey' => false,
			    'conditions' => array("Vehicle.vehicle_type_id = VehicleTypeLocale.vehicle_type_id",'VehicleTypeLocale.lang_code'=>$langCode),
			)
		    )
		)
	);
	
	$vechicleData = $this->find('all',array('conditions'=>array('Vehicle.user_id'=>$userId)));
	return $vechicleData;
	
    }

}

?>