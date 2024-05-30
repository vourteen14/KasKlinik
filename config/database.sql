CREATE TABLE `transaction_out`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `information` TEXT NOT NULL,
    `total_price` DOUBLE NOT NULL
);
CREATE TABLE `report`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `patient_id` BIGINT NOT NULL,
    `transaction_id` BIGINT NOT NULL,
    `information` TEXT NOT NULL
);
CREATE TABLE `transaction_in`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `action_id` BIGINT NOT NULL,
    `patient_id` BIGINT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `doctor` VARCHAR(255) NOT NULL,
    `total_price` DOUBLE NOT NULL
);
CREATE TABLE `patient`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `fullname` VARCHAR(255) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL,
    `category` VARCHAR(255) NOT NULL,
    `assurance` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE `user`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL,
    `fullname` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL
);
CREATE TABLE `receipt`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` BIGINT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `addressed_to` VARCHAR(255) NOT NULL,
    `total_price` DOUBLE NOT NULL
);
CREATE TABLE `transaction`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `transaction_id` BIGINT NOT NULL,
    `type` VARCHAR(255) NOT NULL,
    `comment` TEXT NOT NULL,
    `suppliers` VARCHAR(255) NOT NULL,
    `price` DOUBLE NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE `action`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `patient_id` BIGINT NOT NULL,
    `notes` TEXT NOT NULL,
    `diagnosis` TEXT NOT NULL,
    `medicine` TEXT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE `report_details`(
    `id` BIGINT UNSIGNED NOT NULL PRIMARY KEY
);
ALTER TABLE
    `action` ADD CONSTRAINT `action_patient_id_foreign` FOREIGN KEY(`patient_id`) REFERENCES `patient`(`id`);
ALTER TABLE
    `report_details` ADD CONSTRAINT `report_details_id_foreign` FOREIGN KEY(`id`) REFERENCES `report`(`id`);
ALTER TABLE
    `receipt` ADD CONSTRAINT `receipt_transaction_id_foreign` FOREIGN KEY(`transaction_id`) REFERENCES `transaction_out`(`id`);
ALTER TABLE
    `transaction_in` ADD CONSTRAINT `transaction_in_patient_id_foreign` FOREIGN KEY(`patient_id`) REFERENCES `patient`(`id`);
ALTER TABLE
    `receipt` ADD CONSTRAINT `receipt_transaction_id_foreign` FOREIGN KEY(`transaction_id`) REFERENCES `transaction_in`(`id`);
ALTER TABLE
    `transaction_in` ADD CONSTRAINT `transaction_in_action_id_foreign` FOREIGN KEY(`action_id`) REFERENCES `action`(`id`);
ALTER TABLE
    `report` ADD CONSTRAINT `report_patient_id_foreign` FOREIGN KEY(`patient_id`) REFERENCES `patient`(`id`);
ALTER TABLE
    `report` ADD CONSTRAINT `report_transaction_id_foreign` FOREIGN KEY(`transaction_id`) REFERENCES `transaction_in`(`id`);
ALTER TABLE
    `transaction` ADD CONSTRAINT `transaction_transaction_id_foreign` FOREIGN KEY(`transaction_id`) REFERENCES `transaction_in`(`id`);
ALTER TABLE
    `transaction` ADD CONSTRAINT `transaction_transaction_id_foreign` FOREIGN KEY(`transaction_id`) REFERENCES `transaction_out`(`id`);

ALTER TABLE
    `transaction_out` AUTO_INCREMENT=1130;
ALTER TABLE
    `report` AUTO_INCREMENT=1230;
ALTER TABLE
    `transaction_in` AUTO_INCREMENT=1330;
ALTER TABLE
    `patient` AUTO_INCREMENT=1430;
ALTER TABLE
    `user` AUTO_INCREMENT=1530;
ALTER TABLE
    `receipt` AUTO_INCREMENT=1630;
ALTER TABLE
    `transaction` AUTO_INCREMENT=1730;
ALTER TABLE
    `action` AUTO_INCREMENT=1830;
ALTER TABLE
    `report_details` AUTO_INCREMENT=1930;