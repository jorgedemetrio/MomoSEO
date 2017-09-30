CREATE VIEW #__CONTATOS (id, name, ID_USER) AS
(SELECT
	CONTATOS.ID as id, USER.name, CONTATOS.ID_USER
FROM 
((SELECT contato.id_usuario  AS ID, LISTA.id_usuario_criador AS ID_USER FROM 
		#__angelgirls_amizade_lista_contato AS contato INNER JOIN  
        #__angelgirls_amizade_lista AS LISTA ON LISTA.id = contato.id_lista)
UNION
(SELECT id_usuario_solicidante AS ID, id_usuario_solicitado AS ID_USER FROM #__angelgirls_amizade)
UNION
(SELECT id_usuario_solicitado AS ID, id_usuario_solicidante AS ID_USER FROM #__angelgirls_amizade)
UNION
(SELECT id_usuario_seguido AS ID, id_usuario_seguidor AS ID_USER FROM #__angelgirls_seguindo)
UNION
(SELECT id_usuario_seguidor AS ID, id_usuario_seguido AS ID_USER FROM #__angelgirls_seguindo)) AS CONTATOS INNER JOIN
#__users USER ON USER.id = CONTATOS.ID
GROUP BY
	CONTATOS.ID, USER.name, CONTATOS.ID_USER)
