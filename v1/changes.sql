ALTER TABLE `bookings`
	ADD COLUMN `pida_fee` FLOAT(6,2) NULL DEFAULT '0.00' COMMENT 'pida fee' AFTER `price`;

update bookings set pida_fee = '10.00';


// 22/12/2016
ALTER TABLE `transactions`
	ADD COLUMN `admin_pida_fee` FLOAT(6,2) NOT NULL DEFAULT '0.00' COMMENT 'Admin Pida fee' AFTER `admin_percent`,
	ADD COLUMN `admin_total_amount` FLOAT(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Admin total amount' AFTER `admin_pida_fee`;

update transactions set admin_pida_fee = '4.00';
update transactions set admin_total_amount = admin_amount+admin_pida_fee;
update transactions set driver_amount = driver_amount - admin_pida_fee;

// 2/1/2016
ALTER TABLE `users`
	ADD COLUMN `lang_code` VARCHAR(4) NULL DEFAULT NULL COMMENT 'language code' AFTER `dob`;
