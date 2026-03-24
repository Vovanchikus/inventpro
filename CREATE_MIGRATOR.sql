-- CREATE_MIGRATOR.sql
-- Run this on your MySQL server (as root) to create a read-only user for migration.
-- Replace 'migrator_tmp_pass' with a strong password before running.

-- Create or update migrator user (idempotent)
-- Replace 'migrator_tmp_pass' with desired password before running, or run the helper script which does substitution.
CREATE USER IF NOT EXISTS 'migrator'@'localhost' IDENTIFIED BY 'migrator_tmp_pass';
ALTER USER 'migrator'@'localhost' IDENTIFIED BY 'migrator_tmp_pass';
GRANT SELECT, SHOW VIEW ON `inventpro-test`.* TO 'migrator'@'localhost';
FLUSH PRIVILEGES;

-- If you encounter authentication issues with some clients, use mysql_native_password variant:
-- ALTER USER 'migrator'@'localhost' IDENTIFIED WITH mysql_native_password BY 'migrator_tmp_pass';
-- FLUSH PRIVILEGES;

-- After creating/updating the user, update .env.migration MYSQL_PASSWORD if you changed the password.
