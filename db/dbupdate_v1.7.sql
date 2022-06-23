/*
Actualització de la DB a partir de la versió 1.7
*/

ALTER TABLE SISTEMA ADD google_client_id VARCHAR(100);
ALTER TABLE SISTEMA ADD google_client_secret VARCHAR(100);
ALTER TABLE SISTEMA ADD google_redirect_uri VARCHAR(100);
ALTER TABLE SISTEMA ADD moodle_url VARCHAR(100);
ALTER TABLE SISTEMA ADD moodle_ws_token VARCHAR(100);
ALTER TABLE SISTEMA ADD ipdata_api_key VARCHAR(100);

/*
 * CopiaTutors
 *
 * Copia els tutors d'un any acadèmic a un altre.
 *
 * @param integer AnyAcademicIdOrigen Identificador de l'any acadèmic origen.
 * @param integer AnyAcademicIdDesti Identificador de l'any acadèmic destí.
 */
DELIMITER //
CREATE PROCEDURE CopiaTutors(IN AnyAcademicIdOrigen INT, IN AnyAcademicIdDesti INT)
BEGIN
    DECLARE _curs_id, _professor_id, _curs_id_desti INT;
    DECLARE _grup_tutoria VARCHAR(2);
    DECLARE done INT DEFAULT FALSE;

    BEGIN
        DECLARE cur CURSOR FOR
            SELECT T.curs_id, T.professor_id, T.grup_tutoria, 
            (   SELECT curs_id 
                FROM CURS C2 
                LEFT JOIN CICLE_PLA_ESTUDI CPE2 ON (CPE2.cicle_pla_estudi_id=C2.cicle_formatiu_id) 
                WHERE CPE2.cicle_formatiu_id=CPE.cicle_formatiu_id AND C2.nivell=C.nivell AND CPE2.any_academic_id=AnyAcademicIdDesti
            ) AS CursIdDesti
            FROM TUTOR T
            LEFT JOIN CURS C ON (C.curs_id=T.curs_id)
            LEFT JOIN CICLE_PLA_ESTUDI CPE ON (CPE.cicle_pla_estudi_id=C.cicle_formatiu_id)
            WHERE CPE.any_academic_id=AnyAcademicIdOrigen;
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
        OPEN cur;
        read_loop: LOOP
            FETCH cur INTO _curs_id, _professor_id, _grup_tutoria, _curs_id_desti;
            IF done THEN
                LEAVE read_loop;
            END IF;
            INSERT INTO TUTOR (curs_id, professor_id, grup_tutoria) VALUES (_curs_id_desti, _professor_id, _grup_tutoria);
        END LOOP;
        CLOSE cur;
    END;
END //
DELIMITER ;

/*
 * CopiaProgramacions
 *
 * Copia les programacions d'un any acadèmic a un altre.
 *
 * @param integer AnyAcademicIdOrigen Identificador de l'any acadèmic origen.
 * @param integer AnyAcademicIdDesti Identificador de l'any acadèmic destí.
 */
DELIMITER //
CREATE PROCEDURE CopiaProgramacions(IN AnyAcademicIdOrigen INT, IN AnyAcademicIdDesti INT)
BEGIN
    DECLARE _modul_pla_estudi_id, _modul_pla_estudi_id_desti INT;
    DECLARE _metodologia, _criteris_avaluacio, _recursos TEXT;
    DECLARE done INT DEFAULT FALSE;

    BEGIN
        DECLARE cur CURSOR FOR
        SELECT MPE.modul_pla_estudi_id, MPE.metodologia, MPE.criteris_avaluacio, MPE.recursos,
            (   SELECT modul_pla_estudi_id 
                FROM MODUL_PLA_ESTUDI MPE2
                LEFT JOIN CICLE_PLA_ESTUDI CPE2 ON (CPE2.cicle_pla_estudi_id=MPE2.cicle_pla_estudi_id)
                WHERE MPE2.modul_professional_id=MPE.modul_professional_id AND CPE2.any_academic_id=5
            ) AS ModulPlaEstudiIdDesti			
            FROM MODUL_PLA_ESTUDI MPE
            LEFT JOIN CICLE_PLA_ESTUDI CPE ON (CPE.cicle_pla_estudi_id=MPE.cicle_pla_estudi_id)
            WHERE CPE.any_academic_id=4;        
        DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
        OPEN cur;
        read_loop: LOOP
            FETCH cur INTO _modul_pla_estudi_id, _metodologia, _criteris_avaluacio, _recursos, _modul_pla_estudi_id_desti;
            IF done THEN
                LEAVE read_loop;
            END IF;
            UPDATE MODUL_PLA_ESTUDI SET metodologia=_metodologia, criteris_avaluacio=_criteris_avaluacio, recursos=_recursos WHERE modul_pla_estudi_id=_modul_pla_estudi_id_desti;
        END LOOP;
        CLOSE cur;
    END;
END //
DELIMITER ;

