CREATE VIEW module_credit_merge AS
SELECT
`id` as credit_id ,`source`, `type`, `credit_tag`, `company_name` as credit_name, `loan_amounts`, `can_use_amounts`, `interest_rate`, `repayment_method`,`expiration_date`, `loan_username`, `created_at`, `loan_deadline`,`contract_no` from `module_credit_factoring`
UNION ALL
SELECT
`id` as credit_id ,`source`, `type`, `credit_tag`, `company_name` as credit_name, `loan_amounts`,`can_use_amounts`, `interest_rate`, `repayment_method`,`expiration_date`,`loan_username` ,`created_at`, `loan_deadline`,`contract_no`  from `module_credit_loan`
UNION ALL
SELECT
`id` as credit_id ,`source`, `type`, `credit_tag`, `company_name` as credit_name, `loan_amounts`, `can_use_amounts`,`interest_rate`, `repayment_method`,`expiration_date`,`loan_username` ,`created_at`, `loan_deadline`,`contract_no`  from `module_credit_building_mortgage`
UNION ALL
SELECT
`id` as credit_id ,`source`, `type`, `credit_tag`, `company_name` as credit_name, `loan_amounts`, `can_use_amounts`,`interest_rate`, `repayment_method`,`expiration_date`, `loan_username` ,`created_at`, `loan_deadline`,`contract_no`  from `module_credit_group`
UNION ALL
SELECT
`id` as credit_id ,`source`, `type`, `credit_tag`, `plan_name` as credit_name, `loan_amounts`, `can_use_amounts`, `interest_rate`,`repayment_method`,`expiration_date`, '' as `loan_username` ,`created_at`, `loan_deadline`,`contract_no`  from `module_credit_nine`
UNION ALL
SELECT
`id` as credit_id ,`source`, `type`, `credit_tag`, `company_name` as credit_name, `loan_amounts`, `can_use_amounts`, `interest_rate`, `repayment_method`,`expiration_date`, `loan_username` ,`created_at`, `loan_deadline`,`contract_no`  from `module_credit_third` 