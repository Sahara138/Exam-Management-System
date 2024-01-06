--User Table
DROP TABLE user_tb CASCADE CONSTRAINTS;

CREATE TABLE user_tb (
    u_id       NUMBER(10),
    u_name     VARCHAR2(50) NOT NULL,
    u_email    VARCHAR2(50) NOT NULL,
    u_phone    VARCHAR2(11) NOT NULL,
    u_password VARCHAR2(50) NOT NULL,
    u_type     VARCHAR2(10) NOT NULL,
    a_status   VARCHAR2(50) NOT NULL
);

ALTER TABLE user_tb ADD CONSTRAINT user_tb_pk PRIMARY KEY ( u_id );

DROP SEQUENCE user_tb_sq;

CREATE SEQUENCE user_tb_sq START WITH 1001 INCREMENT BY 1 MAXVALUE 9999 NOCYCLE NOCACHE;

ALTER TABLE user_tb ADD UNIQUE ( u_email );

--Admin Table

DROP TABLE admin_tb CASCADE CONSTRAINTS;

CREATE TABLE admin_tb (
    a_id NUMBER(10),
    u_id VARCHAR2(10) NOT NULL
);

ALTER TABLE admin_tb ADD CONSTRAINT admin_pk PRIMARY KEY ( a_id );

DROP SEQUENCE admin_sq;

CREATE SEQUENCE admin_sq START WITH 1001 INCREMENT BY 1 MAXVALUE 1100 NOCYCLE NOCACHE;

ALTER TABLE admin_tb
    ADD CONSTRAINT admin_fk FOREIGN KEY ( a_id )
        REFERENCES user_tb ( u_id )
            ON DELETE CASCADE;

--Question Table

DROP TABLE question_tb CASCADE CONSTRAINTS;

CREATE TABLE question_tb (
    q_id    NUMBER(10),
    q_sub   VARCHAR2(255) NOT NULL,
    q_ques  VARCHAR2(255) NOT NULL,
    q_ans   VARCHAR2(255) NOT NULL,
    q_marks VARCHAR2(255) NOT NULL,
    u_id    NUMBER(10) NOT NULL
);

ALTER TABLE question_tb ADD CONSTRAINT question_pk PRIMARY KEY ( q_id );

ALTER TABLE question_tb
    ADD CONSTRAINT question_fk FOREIGN KEY ( u_id )
        REFERENCES user_tb ( u_id )
            ON DELETE CASCADE;

DROP SEQUENCE question_sq;

CREATE SEQUENCE question_sq START WITH 1001 INCREMENT BY 1 MAXVALUE 9999 NOCYCLE NOCACHE;

--Result Table

DROP TABLE result_tb CASCADE CONSTRAINTS;

CREATE TABLE result_tb (
    r_id    NUMBER(10),
    r_marks DECIMAL(5, 2) DEFAULT 0.00 NOT NULL,
    u_id    NUMBER(10) NOT NULL,
    q_id    NUMBER(10) NOT NULL
);

ALTER TABLE result_tb ADD CONSTRAINT result_pk PRIMARY KEY ( r_id );

ALTER TABLE result_tb
    ADD CONSTRAINT result_student_fk FOREIGN KEY ( u_id )
        REFERENCES user_tb ( u_id )
            ON DELETE CASCADE;

ALTER TABLE result_tb
    ADD CONSTRAINT result_ques_fk FOREIGN KEY ( q_id )
        REFERENCES question_tb ( q_id )
            ON DELETE CASCADE;

DROP SEQUENCE result_sq;

CREATE SEQUENCE result_sq START WITH 1001 INCREMENT BY 1 MAXVALUE 9999 NOCYCLE NOCACHE;

--Log Table

DROP TABLE log_tb CASCADE CONSTRAINTS;

CREATE TABLE log_tb (
    log_id      NUMBER(10),
    user_sys    VARCHAR2(30),
    log_details VARCHAR2(50),
    log_time    VARCHAR2(30)
);

ALTER TABLE log_tb ADD CONSTRAINT log_pk PRIMARY KEY ( log_id );

DROP SEQUENCE log_sq;

CREATE SEQUENCE log_sq START WITH 1001 INCREMENT BY 1 MAXVALUE 9999 NOCYCLE NOCACHE;

---------------------------------------------------------------------------------------------

------ Trigger

---------------------------------------------------------------------------------------------

--Trigger 1
CREATE OR REPLACE TRIGGER validate_working_hour BEFORE INSERT OR UPDATE OR DELETE on user_tb
BEGIN
     IF to_char(sysdate, 'DY') in ('SAT', 'FRI') OR to_char(sysdate, 'HH24') NOT BETWEEN '08' AND '17' then
        raise_application_error(-20201, 'YOU BETTER COME IN REGULAR TIME');
     END IF;
END;

ALTER TRIGGER validate_working_hour DISABLE;

--Trigger 2
CREATE OR REPLACE TRIGGER save_log AFTER
    INSERT OR UPDATE OR DELETE ON user_tb
    FOR EACH ROW
ENABLE DECLARE
    database_user VARCHAR2(30);
    database_user_date VARCHAR2(30);
BEGIN
    SELECT
        user,
        to_char(sysdate, 'MM-DD-YYYY HH24:MI:SS') "NOW"
    INTO
        database_user,
        database_user_date
    FROM
        dual;

    IF inserting THEN
        INSERT INTO log_tb VALUES (
            log_sq.NEXTVAL,
            database_user,
            'Inserted a user',
            database_user_date
        );

    ELSIF updating THEN
        INSERT INTO log_tb VALUES (
            log_sq.NEXTVAL,
            database_user,
            'Updated a user',
            database_user_date
        );

    ELSIF deleting THEN
        INSERT INTO log_tb VALUES (
            log_sq.NEXTVAL,
            database_user,
            'Deleted a user',
            database_user_date
        );

    ELSE
        INSERT INTO log_tb VALUES (
            log_sq.NEXTVAL,
            database_user,
            'Undefined changes in the user table',
            database_user_date
        );

    END IF;

END;

--Trigger 3
CREATE OR REPLACE TRIGGER set_admin_trg AFTER INSERT on user_tb
BEGIN 
    IF u_type = 'admin' THEN 
    INSERT INTO admin_tb VALUES (admin_sq.NEXTVAL, u_id);
END;
    
-- END;

---------------------------------------------------------------------------------------------

------ View

---------------------------------------------------------------------------------------------
--View 1
CREATE OR REPLACE VIEW viewadmin_tbs AS
    ( SELECT
        a.a_id       "a_id",
        u.u_id       "u_id",
        u.u_name     "a_name",
        u.u_email    "a_email",
        u.u_phone    "a_phone",
        u.u_password "a_password",
        u.u_type     "utype"
    FROM
        user_tb  u,
        admin_tb a
    WHERE
        u.u_id = a.u_id
    );

--View 2
CREATE OR REPLACE VIEW view_students AS
    ( SELECT
        u_id       "u_id",
        u_name     "a_name",
        u_email    "a_email",
        u_phone    "a_phone",
        u_password "a_password",
        u_type     "utype"
    FROM
        user_tb
    WHERE
        u_type = 'student'
    );

--View 3
CREATE OR REPLACE VIEW view_teacher AS
    ( SELECT
        u_id       "u_id",
        u_name     "a_name",
        u_email    "a_email",
        u_phone    "a_phone",
        u_password "a_password",
        u_type     "utype"
    FROM
        user_tb
    WHERE
        u_type = 'teacher'
    );

--View 4
CREATE OR REPLACE VIEW view_marks AS
    ( SELECT
        r.u_id    "U_ID",
        u.u_name  "U_NAME",
        q.q_ques  "Q_QUES",
        q.q_ans   "Q_ANS",
        q.q_marks "Q_MARKS",
        r.r_marks "R_MARKS"
    FROM
        user_tb     u,
        question_tb q,
        result_tb   r
    WHERE
            r.u_id = u.u_id
        AND r.q_id = q.q_id
    );

--View 5
CREATE OR REPLACE VIEW view_ques AS
    ( SELECT
        q.q_id    "Q_ID",
        q.q_sub  "Q_SUB",
        q.q_ques  "Q_QUES",
        q.q_ans   "Q_ANS",
        q.q_marks "Q_MARKS",
        q.u_id "T_ID",
        u.u_name "T_NAME"
    FROM
        user_tb     u,
        question_tb q
    WHERE
        q.u_id = u.u_id
    );

---------------------------------------------------------------------------------------------

------ Procedure

---------------------------------------------------------------------------------------------
--Procedure 1
CREATE OR REPLACE PROCEDURE insert_user (
    uname     IN user_tb.u_name%TYPE,
    uemail    IN user_tb.u_email%TYPE,
    uphone    IN user_tb.u_phone%TYPE,
    upassword IN user_tb.u_password%TYPE,
    utype     IN user_tb.u_type%TYPE,
    astatus   IN user_tb.a_status%TYPE
) IS
BEGIN
    INSERT INTO user_tb VALUES (
        user_tb_sq.NEXTVAL,
        uname,
        uemail,
        uphone,
        upassword,
        utype,
        astatus
    );

END;

--Procedure 2 
CREATE OR REPLACE PROCEDURE update_user (
    userid IN user_tb.u_id%TYPE,
    uname  IN user_tb.u_name%TYPE,
    uemail IN user_tb.u_email%TYPE,
    uphone IN user_tb.u_phone%TYPE
) IS
BEGIN
    UPDATE user_tb
    SET
        u_name = uname,
        u_email = uemail,
        u_phone = uphone
    WHERE
        u_id = userid;

END;

--Procedure 3
CREATE OR REPLACE PROCEDURE delete_user (
    userid IN user_tb.u_id%TYPE
) IS
BEGIN
    DELETE user_tb
    WHERE
        u_id = userid;

END;

--Procedure 4
CREATE OR REPLACE PROCEDURE set_ques (
    sub    IN question_tb.q_sub%TYPE,
    ques   IN question_tb.q_ques%TYPE,
    ans    IN question_tb.q_ans%TYPE,
    mark   IN question_tb.q_marks%TYPE,
    userid IN question_tb.u_id%TYPE
) IS
BEGIN
    INSERT INTO question_tb VALUES (
        question_sq.NEXTVAL,
        sub,
        ques,
        ans,
        mark,
        userid
    );

END;

--Procedure 5
CREATE OR REPLACE PROCEDURE set_result (
    mark   IN result_tb.r_marks%TYPE,
    userid IN result_tb.u_id%TYPE,
    quesid IN result_tb.q_id%TYPE
) IS
BEGIN
    INSERT INTO result_tb VALUES (
        result_sq.NEXTVAL,
        mark,
        userid,
        quesid
    );

END;

--PROCEDURE 6
CREATE OR REPLACE PROCEDURE change_pass (
    userid IN user_tb.u_id%TYPE,
    pass   IN user_tb.u_pass%TYPE
) IS
BEGIN
    UPDATE user_tb
    SET
        u_pass = pass
    WHERE
        u_id = userid;

END;



--Data Entry
--------USER Table--------------------
INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Default Admin',
    'admin@sys.com',
    '01315580959',
    'password',
    'admin',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Asif Hasan Abir',
    'asif@gmail.com',
    '01315580959',
    'password',
    'student',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Najim Ullah Rifat',
    'najim@gmail.com',
    '01635357537',
    'password',
    'student',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Sahara Islam',
    'sislam@gmail.com',
    '01533827138',
    'password',
    'student',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Shondip biswas',
    'shondipb@gmail.com',
    '01759508607',
    'password',
    'student',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Abdullah Al Abir',
    'abdullahal@gmail.com',
    '0179221450',
    'password',
    'student',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Jannatul Ferdous',
    'jannatf@gmail.com',
    '01885552911',
    'password',
    'teacher',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Shafi Raihan',
    'shafiraihan@gmail.com',
    '01318055959',
    'password',
    'teacher',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Atiqur Rahman',
    'atiq@gmail.com',
    '01751841169',
    'password',
    'teacher',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Tareq Hasan',
    'tareq@gmailcom',
    '01303043458',
    'password',
    'teacher',
    'true'
);

INSERT INTO user_tb VALUES (
    user_tb_sq.NEXTVAL,
    'Dr. Hasan Md. Mahadhi',
    'mmahadhi@gmail.com',
    '01720640533',
    'password',
    'teacher',
    'true'
);

--------------Question--------------------

INSERT INTO question_tb VALUES (
    question_sq.NEXTVAL,
    'ISDS',
    'What''s the full form of ISD&SE',
    'Information System Design and Software Engineering',
    10,
    1001
);

INSERT INTO question_tb VALUES (
    question_sq.NEXTVAL,
    'ADMS',
    'What''s the full form of RDBMS?',
    'Relational Database Management System',
    10,
    1001
);

INSERT INTO question_tb VALUES (
    question_sq.NEXTVAL,
    'ADMS',
    'What are the types of normalization in database?',
    '1NF, 2NF, 3NF',
    10,
    1001
);

INSERT INTO question_tb VALUES (
    question_sq.NEXTVAL,
    'ADMS',
    'What''s the full form of DDL?',
    'Data Definition Language',
    10,
    1001
);

INSERT INTO question_tb VALUES (
    question_sq.NEXTVAL,
    'ADMS',
    'What''s the full form of DML?',
    'Data Manipulation Language',
    10,
    1001
);

-----------------Result TB--------------

INSERT INTO result_tb VALUES (
    result_sq.NEXTVAL,
    10,
    1001,
    1001
);

INSERT INTO result_tb VALUES (
    result_sq.NEXTVAL,
    10,
    1002,
    1002
);

INSERT INTO result_tb VALUES (
    result_sq.NEXTVAL,
    10,
    1003,
    1003
);

INSERT INTO result_tb VALUES (
    result_sq.NEXTVAL,
    10,
    1004,
    1004
);

INSERT INTO result_tb VALUES (
    result_sq.NEXTVAL,
    10,
    1005,
    1005
);

INSERT INTO log_tb VALUES (
    log_sq.NEXTVAL,
    1002,
    'admin',
    'Log Details Created',
    TO_DATE('2022/04/21 21:02:44', 'yyyy/mm/dd hh24:mi:ss')
);