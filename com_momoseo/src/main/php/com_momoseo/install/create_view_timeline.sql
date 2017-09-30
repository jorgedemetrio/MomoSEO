CREATE OR REPLACE VIEW #__timeline (id, token, tipo,  titulo, descricao, prioridade, data_publicado, 
	audiencia, acessos, autor1, autorid1, autor2, autorid2, rnd, opt1, opt2, opt3, opt4) AS
(SELECT 
	s.id as id, 
	s.token as token, 
    'SESSOES' as tipo, 
    s.titulo as titulo,
    s.descricao as descricao, 
    0.9 as prioridade, 
    s.publicar as data_publicado, 
	s.audiencia_gostou as audiencia,
    s.audiencia_view as acessos,
	f.nome_artistico autor1, 
	f.token autorid1, 
	m.nome_artistico autor2, 
	m.token	autorid2,
    RAND() as rnd,
    '' AS opt1,
    '' AS opt2,
    '' AS opt3,
    '' AS opt4
FROM 
	#__angelgirls_sessao as s inner join
	#__angelgirls_modelo as m on s.id_modelo_principal = m.id inner join
	#__angelgirls_fotografo as f on s.id_fotografo_principal = f.id
WHERE
	s.status_dado =  'PUBLICADO' AND
    s.publicar <= NOW() 
ORDER BY 
	s.publicar DESC)
UNION 
(SELECT 
	s.id as id, 
	s.alias as token, 
    'CONTENT' as tipo, 
    s.title as titulo, 
    s.introtext as descricao,
    0.5 as prioridade, 
    s.publish_up as data_publicado, 
	s.access as audiencia,
    s.hits as acessos,
	u.name autor1, 
	u.id autorid1, 
	s.created_by_alias autor2, 
	null autorid2,
	RAND() as rnd,
    concat(s.id , ':' , s.alias) AS opt1,
    s.catid AS opt2,
    language AS opt3,
	MID(`images`,LOCATE(':',s.`images`)+2, LOCATE(',',s.`images`)-LOCATE(':',s.`images`)-2) AS opt4
FROM 
	#__content as s inner join
	#__users u on s.created_by = u.id 
WHERE
	s.state =  1 AND
    s.publish_up <= NOW()   AND 
    s.images IS NOT NULL AND
    LENGTH(s.images) > 5
ORDER BY 
	s.publish_up DESC)
UNION
(SELECT 
	s.id, 
	s.token as token, 
    'MODELO' as tipo, 
    s.nome_artistico as titulo, 
    s.meta_descricao as descricao,
    0.4 as prioridade, 
    s.data_criado as data_publicado, 
	s.audiencia_gostou as audiencia,
    s.audiencia_view as acessos,
	null autor1, 
	null autorid1, 
	null autor2, 
	null autorid2,
	RAND() as rnd,
    '' AS opt1,
    '' AS opt2,
    '' AS opt3,
    '' AS opt4
FROM 
	#__angelgirls_modelo as s
WHERE
	s.status_dado NOT IN  ('REMOVIDO','REPROVADO') AND
    s.status_modelo NOT IN  ('REMOVIDO','REPROVADO') AND
    s.foto_perfil <> '' AND
    s.foto_perfil is not null AND 
    s.id IN (SELECT id_modelo_principal as id FROM #__angelgirls_sessao)
ORDER BY 
	s.data_alterado DESC)
UNION 
 (SELECT 
	s.id, 
	s.token as token, 
    'POST' as tipo, 
    '' as titulo, 
    s.texto as descricao,
    0.5 as prioridade, 
    s.data_criado as data_publicado, 
	s.audiencia_gostou as audiencia,
    s.audiencia_view as acessos,
	p.apelido autor1, 
	p.token autorid1, 
	p.tipo autor2, 
	p.id autorid2,
	RAND() as rnd,
    p.id_usuario AS opt1,
    '' AS opt2,
    '' AS opt3,
    '' AS opt4
FROM 
	#__angelgirls_post as s INNER JOIN 
    #__angelgirls_perfil as p on s.id_usuario = p.id_usuario
WHERE
	s.status_dado NOT IN  ('REMOVIDO','REPROVADO') AND
    p.status_dado NOT IN  ('REMOVIDO','REPROVADO') 
ORDER BY 
	s.data_alterado DESC)   
UNION 
 (SELECT 
	s.id, 
	s.token as token, 
    'ALBUM' as tipo, 
    s.titulo as titulo, 
    s.meta_descricao as descricao,
    0.7 as prioridade, 
    s.data_criado as data_publicado, 
	s.audiencia_gostou as audiencia,
    s.audiencia_view as acessos,
	p.apelido autor1, 
	p.token autorid1, 
	p.tipo autor2, 
	p.id autorid2,
	RAND() as rnd,
    p.id_usuario AS opt1,
    '' AS opt2,
    '' AS opt3,
    '' AS opt4
FROM 
	#__angelgirls_album as s INNER JOIN 
    #__angelgirls_perfil as p on s.id_usuario_criador = p.id_usuario
WHERE
	s.status_dado NOT IN  ('REMOVIDO','REPROVADO') AND
	p.status_dado NOT IN  ('REMOVIDO','REPROVADO')
ORDER BY 
	s.s.data_alterado DESC)  
UNION 
 (SELECT 
	s.id, 
	s.token as token, 
    'PROMOCAO' as tipo, 
    s.titulo as titulo, 
    s.meta_descricao as descricao,
    0.9 as prioridade, 
    s.inicio as data_publicado, 
	0 as audiencia,
    0 as acessos,
	null autor1, 
	null autorid1, 
	null autor2, 
	null autorid2,
	RAND() as rnd,
    '' AS opt1,
    '' AS opt2,
    '' AS opt3,
    '' AS opt4
FROM 
	#__angelgirls_promocao as s 
WHERE
	s.status_dado = 'PUBLICADO' AND
    s.inicio <= NOW() AND
    s.fim >= NOW() 
ORDER BY 
	s.s.data_alterado DESC) 
UNION
(SELECT 
	s.id, 
	s.id as token, 
    'BANNER' as tipo, 
    s.custombannercode as titulo, 
    s.description as descricao,
    0.7 as prioridade, 
    IF( FLOOR((RAND() * 5)) >3, NOW(), s.publish_up) as data_publicado, 
	s.clicks as audiencia,
    s.track_impressions as acessos,
	null autor1, 
	null autorid1, 
	null autor2, 
	null autorid2,
	RAND() as rnd,
    s.clickurl AS opt1,
    s.alias AS opt2,
    language AS opt3,
   	s.custombannercode AS opt4
FROM 
	#__banners as s
WHERE
	s.state =  1 AND
    s.publish_up <= NOW()  AND 
    s.publish_down >= NOW() AND
    s.catid = 2
ORDER BY 
	s.publish_up DESC, s.clicks)
ORDER BY data_publicado DESC, prioridade DESC;