SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO';
INSERT INTO
    address
    (id, name, phone, street, created_at)
VALUES
    (0, 'Michal', '506088156','Michalowskiego 41',  NOW()),
    (1, 'Marcin', '502145785','Opata Rybickiego 1', NOW()),
    (2, 'Piotr',  '504212369','Horacego 23',        NOW()),
    (3, 'Albert', '605458547','Jan Pawła 67',       NOW());