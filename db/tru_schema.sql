DROP TABLE IF EXISTS address;
CREATE TABLE address (
    id         INT          UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(255) NOT NULL                          COMMENT 'Name of the person',
    phone      VARCHAR(20)  NOT NULL                          COMMENT 'Phone number of the person',
    street     VARCHAR(255)  NOT NULL                         COMMENT 'Street address of the person',
    created_at DATETIME     NOT NULL                          COMMENT 'Creation time of the entry',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT 'Stores the addresses';