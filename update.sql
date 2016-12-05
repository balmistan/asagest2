truncate sessions;

truncate config;

INSERT INTO `config` (`configName`, `configDescription`, `configValue`, `configTitle`, `configEditable`) VALUES
('criprovince', 'Sigla provincia sede di appartenenza:', 'UD', 'Nella compilazione della scheda famiglia, i comuni di residenza suggeriti verranno scelti in base alla sigla provincia impostata quì', 1),
('check_isee', 'Verifica stato del Certificato (SI/NO):', 'NO', 'Nella scheda famiglia esiste la possibilità di inserire la data di scadenza del documento attestante che la famiglia necessita i viveri. Impostando NO non si verrà avvisati alla sua scadenza', 1),
('notice_isee', 'Preavviso scadenza Certificato giorni:', '30', 'Funziona solo se Verifica stato certificato è impostata a SI. Genera un messaggio di preavviso prima della scadenza', 1),
('distance_distrib', 'Distanza minima tra 2 ritiri giorni:', '60', 'Indica l'' intervallo di giorni minimo da rispettare tra due distribuzioni successive per la stessa famiglia. Genera soltanto un avviso. La distribuzione sarà in ogni caso effettuabile ', 1),
('signature_block', 'Area firma sul blocchetto consegne (SI/NO):', 'NO', 'Consente di rimuovere l'' area firma dal blocchetto consegne', 0),
('init_registri', 'Data manuale su blocchetto (SI/NO):', 'si', 'Data manuale su blocchetto per inserire distribuzioni arretrate', 0),
('yearref', 'Anno di riferimento blocchetto:', '2016', 'Anno di riferimento blocchetto distribuzioni', 0),
('progagea', 'Selezione dei registri agea da utilizzare', '_1', 'Permette di selezionare il programma Agea in corso e quindi i relativi registri.', 1);

RENAME TABLE all8productcumprog1415 TO all8productcum_1;

RENAME TABLE all8registercumprog1415 TO all8registercum_1;

RENAME TABLE allegaticonfigprog1415 TO allegaticonfig_1;



RENAME TABLE all8productcumprog1516 TO all8productcum_2;

RENAME TABLE all8registercumprog1516 TO all8registercum_2;

RENAME TABLE allegaticonfigprog1516 TO allegaticonfig_2;

drop table if exists `adminMenu`;
drop table if exists `images`;
drop table if exists `blockLanguages`;
drop table if exists `galleries`;
drop table if exists `moduleAccess`;
drop table if exists `moduleLanguages`;
drop table if exists `modules`;
drop table if exists `redirects`;
drop table if exists `tempFile`;
drop table if exists `thumbs`;
drop table if exists `userattribs`;
drop table if exists `userInfo`;


