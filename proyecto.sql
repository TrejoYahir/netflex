drop database netflixer;
use netflixer;
show tables;
describe usuario;
describe perfil;
select * from usuario where correo="emiliano@gmail.com";
ALTER TABLE usuario CHANGE contraseña contrasena varchar(100);
SELECT * FROM usuario, perfil WHERE usuario.id_usuario=1 and perfil.id_usuario = usuario.id_usuario;
describe lista;
select * from lista;
describe metraje;
describe serie;
select metraje.* from lista, perfil, metraje where lista.id_perfil=perfil.id_perfil and perfil.id_perfil=3 group by id_metraje;
select * from pelicula; 
SELECT metraje.* FROM usuario,perfil,lista,metraje WHERE usuario.id_usuario=1 and perfil.id_usuario = usuario.id_usuario and perfil.id_perfil=1 and perfil.id_perfil=lista.id_perfil and lista.id_metraje=metraje.id_metraje;
SELECT perfil.* FROM usuario, perfil WHERE usuario.id_usuario=1 and perfil.id_usuario = usuario.id_usuario and perfil.id_perfil=1;
select * from categoria;

update metraje set puntuacion=5 where id_metraje=3;
update serie set imagen="http://www.optimovision.com/wp-content/uploads/2016/09/narcos-estrena-trailer-de-la-segunda-temporada-a.jpg" where id_metraje=6;
update pelicula set imagen="http://www.datereliz.com/wp-content/uploads/2015/06/Need-for-Speed-2-movie-release-date-1.jpg" where id_metraje=11;