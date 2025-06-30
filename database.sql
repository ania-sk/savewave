CREATE TABLE IF NOT EXISTS users(
    id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    username varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    PRIMARY KEY(id),
    UNIQUE KEY(email)
); 


CREATE TABLE IF NOT EXISTS incomes_category_default(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS incomes_category_assigned_to_users(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS incomes(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    income_category_assigned_to_user_id BIGINT(20) UNSIGNED NOT NULL,
    amount DECIMAL(8,2),    
    date_of_income DATETIME NOT NULL,
    income_comment VARCHAR(255),
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(income_category_assigned_to_user_id) REFERENCES incomes_category_assigned_to_users(id)  ON DELETE CASCADE
);

-- default income categories
INSERT INTO incomes_category_default (name)
SELECT 'Salary'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM incomes_category_default WHERE name = 'Salary'

);

INSERT INTO incomes_category_default (name)
SELECT 'Sale'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM incomes_category_default WHERE name = 'Sale'

);

INSERT INTO incomes_category_default (name)
SELECT 'Debt repayment'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM incomes_category_default WHERE name = 'Debt repayment'

);

INSERT INTO incomes_category_default (name)
SELECT 'Other'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM incomes_category_default WHERE name = 'Other'

);


CREATE TABLE IF NOT EXISTS expenses_category_default(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS expenses_category_assigned_to_users(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS expenses(
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id BIGINT(20) UNSIGNED NOT NULL,
    expense_category_assigned_to_user_id BIGINT(20) UNSIGNED NOT NULL,
    amount DECIMAL(8,2),    
    date_of_expense DATETIME NOT NULL,
    expense_comment VARCHAR(255),
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(expense_category_assigned_to_user_id) REFERENCES expenses_category_assigned_to_users(id) ON DELETE CASCADE
);

-- default expense categories
INSERT INTO expenses_category_default (name)
SELECT 'Bills'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM expenses_category_default WHERE name = 'Bills'

);

INSERT INTO expenses_category_default (name)
SELECT 'Shopping'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM expenses_category_default WHERE name = 'Shopping'

);

INSERT INTO expenses_category_default (name)
SELECT 'Entertainment'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM expenses_category_default WHERE name = 'Entertainment'

);

INSERT INTO expenses_category_default (name)
SELECT 'Other'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM expenses_category_default WHERE name = 'Other'
);

ALTER TABLE incomes_category_assigned_to_users
  ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1;

ALTER TABLE expenses_category_assigned_to_users
  ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1;