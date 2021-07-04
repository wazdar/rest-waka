CREATE DATABASE app;

ALTER DATABASE app OWNER TO root;

\connect app

CREATE TABLE public.users
(
    id         bigserial NOT NULL PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName  VARCHAR(100) NOT NULL,
    pesel      VARCHAR(11) UNIQUE NOT NULL,
    created_at  TIMESTAMP DEFAULT NOW(),
    status     SMALLINT  DEFAULT 0
);

