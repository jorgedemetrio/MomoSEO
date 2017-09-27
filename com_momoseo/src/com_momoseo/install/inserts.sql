
INSERT INTO `#__angelgirls_modelo`
(`id_usuario`,`nome_artistico`,`descricao`,`meta_descricao`,`foto_perfil`,`foto_inteira`,`altura`,`peso`,`busto`,`calsa`,`calsado`,`olhos`,
`pele`,`etinia`,`cabelo`,`tamanho_cabelo`,`cor_cabelo`,`outra_cor_cabelo`,`profissao`,`nascionalidade`,`id_cidade_nasceu`,`data_nascimento`,`site`,
`sexo`,`cpf`,`banco`,`agencia`,`conta`,`custo_medio_diaria`,`status_modelo`,`qualificao_equipe`,`audiencia_gostou`,`audiencia_ngostou`,`audiencia_view`,
`id_cidade`,`status_dado`,`id_usuario_criador`,`id_usuario_alterador`,`data_criado`,`data_alterado`)
VALUES
((SELECT id FROM `#__users` WHERE username = 'jessie' ),
'Jessie','Modelo fotografica','Modelo fotografica',null,null,1.46,40,25,35,35,'CASTANHOS','CALCASIANA','OUTRO','LIZO','LONGO','RUIVA',null,
'Modelo','Brasileira',1,'1995-12-14',null,'F','01234567890',null,null,null,0,'ATIVO',0,0,0,0,1,'ATIVO',
(SELECT id FROM `#__users` WHERE username = 'jessie' ),
(SELECT id FROM `#__users` WHERE username = 'jessie' ),
NOW(),NOW());

update `#__angelgirls_modelo` SET foto_inteira = '1_inteira.jpg', foto_perfil = '1_perfil.jpg' WHERE id = 1;





INSERT INTO `#__angelgirls_promocao`
(`titulo`,`nome_foto`,`descricao`,`meta_descricao`,`inicio`,`fim`,`publicar`,
`status_dado`,`id_usuario_criador`,`id_usuario_alterador`,`data_criado`,`data_alterado`)
VALUES
('Mande a sua foto e concorra a um SET','1.jpg',
'Promoção valida apenas para São Paulo.',
'Mande a sua foto e concorra a um SET. <br/>Promoção valida apenas para São Paulo.',
'2015-01-01','2015-12-01','2015-01-01','ATIVO',
(SELECT id FROM `#__users` WHERE username = 'jorge' ),
(SELECT id FROM `#__users` WHERE username = 'jorge' ),
NOW(),
NOW());



INSERT INTO `#__angelgirls_sessao`
(`titulo`,`nome_foto`,`executada`,`descricao`,`historia`,`comentario_fotografo`,`comentario_modelos`,`comentario_equipe`,
`meta_descricao`,`id_agenda`,`id_tema`,`id_modelo_principal`,`id_modelo_secubdaria`,`id_locacao`,`id_fotografo_principal`,
`id_fotografo_secundario`,`id_figurino_principal`,`id_figurino_secundario`,`audiencia_gostou`,`audiencia_ngostou`,
`audiencia_view`,`publicar`,`status_dado`,`id_usuario_criador`,`id_usuario_alterador`,`data_criado`,`data_alterado`)
(SELECT CONCAT('SESSAO ', RAND(), ' ', NOW()) AS `titulo`,
`nome_foto`,`executada`,`descricao`,`historia`,`comentario_fotografo`,`comentario_modelos`,`comentario_equipe`,`meta_descricao`,
`id_agenda`,`id_tema`,`id_modelo_principal`,`id_modelo_secubdaria`,`id_locacao`,`id_fotografo_principal`,`id_fotografo_secundario`,
`id_figurino_principal`,`id_figurino_secundario`,`audiencia_gostou`,`audiencia_ngostou`,`audiencia_view`,`publicar`,'PUBLICADO' AS `status_dado`,
`id_usuario_criador`,`id_usuario_alterador`,NOW() AS `data_criado`,NOW() AS `data_alterado` FROM `#__angelgirls_sessao`);




UPDATE #__angelgirls_sessao SET status_dado = 'PUBLICADO';

INSERT INTO `#__angelgirls_foto_sessao`
(
`titulo`,
`descricao`,
`meta_descricao`,
`id_sessao`,
`audiencia_gostou`,
`audiencia_ngostou`,
`audiencia_view`,
`status_dado`,
`id_usuario_criador`,
`id_usuario_alterador`,
`data_criado`,
`data_alterado`)
VALUES
(
'FOTO 2',
'Foto sessao 1',
'Foto sessao 1',
2,
0,
0,
0,
'NOVO',
(SELECT id FROM `#__users` WHERE username = 'jorge' ),
(SELECT id FROM `#__users` WHERE username = 'jorge' ),
NOW(),
NOW());
UPDATE ag_angelgirls_foto_sessao SET titulo = concat('FOTO ' , id) WHERE ID > 0;
