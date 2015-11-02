// Questo plugin permette di aggiornare alcuni contenuti di una pagina web servendosi di una chiamata ad una pagina lato server.
// Il plugin può passare alcuni parametri in ingresso a tale pagina leggendoli direttamente da alcuni campi di testo presenti.
// Andranno in tal caso forniti gli id dei campi contenenti i dati. Le variabili inviate in POST / GET dal plugin avranno lo stesso nome degli id dei campi dai quali sono stati prelevati i valori.

(function($) {
	$.fn.update = function(options) {
		// valori di default
		var config = {
			'method' : 'POST', //Metodo GET o POST per inviare i dati al server
			'ajaxpage' : '', //Pagina che elabora la richiesta lato server
			'ids' : '', //E' una stringa contenente gli id delle input type="text" o type="hidden" (eventualmente separati da una virgola) da cui leggere i dati da inviare come parametri al server. Il nome dato all' id diventerà il name dei dati inviati al server e il contenuto del textfield il valore.
			'timerefresh' : 3600, //intervallo di refresh contenuti (in secondi)
			'userefresh' : 1, //Attiva / Disattiva l' aggiornamento dei contenuti
			'fade' : 1,       //fade sul cambio immagine
			'debug' : 0
		};

		if (options)
			$.extend(config, options);
		//conterrà gli id degli elementi
		var arr_ids = Array();
		// conterrà i dati da inviare alla pagina lato server
		var str_send = "";
		//conterrà i dati ricevuti in risposta dal server
		var arr_output = Array();

		var arr_send_failure = Array();
		//Conterrà gli id degli elementi di cui non si sono riusciti ad inviare i valori al server (La stringa è accessibile per il debug)

		var arr_update_failure = Array();
		//Conterrà gli id degli elementi che non sono stati aggiornati sulla pagina a causa di qualche errore (La stringa è accessibile per il debug)

		// La fase di inizializzazione mi permetterà di avere un array con gli id delle textbox da cui prelevare i dati da inviare in input alla
		//effettuo le varie inizializzazioni del plugin
		init();
		//chiamo la funzione process che gestisce l' intera sequenza di aggiornamento
		process();

		//////////////////////  BLOCCO DEBUG  //////////////////
		if (config['debug']) {
			//console.log("arr_ids", JSON.stringify(arr_ids))
			if (arr_send_failure.length)
				console.log("Dati non inviati al server per gli elementi con i seguenti id non sono stati trovati", JSON.stringify(arr_send_failure))
		}

		////////////////////////////////////////////////////////

		//////////////// IMPLEMENTAZIONE FUNZIONI  /////////////

		function init() {
			//Assegno ad arr_ids gli id degli input field (presenti nella stringa ids separati dda virgola)
			config['ids'].replace(' ', '');
			//rimuovo gli eventuali spazi
			if (config['ids'] != "") {
				arr_ids = config['ids'].split(",");
				//verifico la reale presenza degli id passati in input (rimuovendoli eventualmente dall' array)
				for (var i = 0; i < arr_ids.length; i++)
					if (!$("#" + arr_ids[i]).length) {
						arr_ids.splice(i, 1);
						arr_send_failure.push(arr_ids[i]);
					}
			}
		}// close function init()

		//Restituisce l' array con i parametri di input da inviare alla pagina Ajax (da chiamare ogni volta che si effettua una nuova richiesta perchè i dati contenuti negli input field potrebbero essere cambiati)
		function inputPrepare() {
			str_send = ""

			for (var i in arr_ids) {
				//arr_send[arr_ids[i]] = $("#" + arr_ids[i]).val();
				str_send += arr_ids[i] + '=' + encodeURI($("#" + arr_ids[i]).val()) + '&'
			}//close for

			//Rimuovo l'ultimo &
			str_send = str_send.replace(/&([^&]*)$/, '$1')

		}// close function inputPrepare()

		//Gestisce l' intera sequenza di operazioni
		function process() {
			//preparo l' array con i dati da inviare al server
			inputPrepare();

			//invio la richiesta al server
			send();

		}// close function process()

		//invia le richieste al server
		function send() {

			$.ajax({
				type : config['method'],
				url : config['ajaxpage'],
				cache : false, //questo perchè anche se invio gli stessi dati in input, la risposta del server sarà differente. Con cache: true la richiesta Ajax non sarebbe stata effettuata le volte successive e i dati sarebbero stati recuperati direttamente dalla cache.
				data : str_send,
				success : function(resp) {

					arr_output = JSON.parse(resp);
					responseCallBack();
				},
				error : function(ts) {
					if (config['debug']) {
						var res = ts.responseText;
						console.log("Error resp: ", res)
					}
				}
			});
		}//close function send()

		//Questa funsione viene eseguita appena giungono i dati in risposta dal server (immediatamente dopo l' aggiornamento dell' arr_output).
		function responseCallBack() {
			//Aggiorno i contenuti sulla pagina
			updateHtml();

			if (config['debug']) {
				if (arr_update_failure.length)
					console.log("Dati non aggiornati sulla pagina perchè gli elementi con i seguenti id non sono stati trovati", JSON.stringify(arr_update_failure))
			}

			//Imposto l' eventuale timeout
			if (config['userefresh'])
				setTimeout(function() {
					process()
				}, config['timerefresh'] * 1000);
		}

		//Aggiorna contenuto pagina
		function updateHtml() {
			//alert(JSON.stringify(arr_output))
			arr_update_failure = Array()//resetto l' array
			for (var idelem in arr_output) {
				var obj = $("#" + idelem);

				var tagname = $(obj).prop("tagName")
				if (tagname === undefined) {
					arr_update_failure.push(idelem);
					continue;
				}

				switch(tagname.toLowerCase()) {
					case "input":
						$(obj).val(arr_output[idelem]['value'])
						break;
					case "p":
					case "div":
					case "span":
					case "textarea":
						$(obj).html(arr_output[idelem]['value'])
						break;
					case "a":
						$(obj).attr("href", arr_output[idelem]['value'])
						break;
					case "img":
						//if ($(obj).attr("src") != arr_output[idelem]['value']) {
							$(obj).animate({
								"opacity" : config['fade']//Ho la possibilità di gestire un fade impostando ad esempio a 0.3
							}, 800).
							//Mi assicuro che l' immagine non venga recuperata dalla cache aggiungendo un valore random.
							attr("src", arr_output[idelem]['value']+"?r="+Math.floor((Math.random() * 10000) + 1))
							.animate({
								"opacity" : 1
							}, 500)
						//}
						//$(obj).attr("src", arr_output[idelem]['value'] + "?r=" + Math.floor((Math.random() * 10000) + 1))
						break;
					default:
						alert("update.plugin -> elemento sconosciuto nella pagina")
						break;
				}//close switch

			}//close for

		}//close function updateHtml()

		//Questo blocco risolve il problema del freezing js e ha senso utilizzarlo solo nel caso in cui è attivo l' aggiornamento automatico.
		if (config['userefresh']) {
			//Se una pagina non è visualizzata (è ad esempio ridotta ad icona) il browser congela l' esecuzione di js quindi arresta i timer per tutto il tempo.
			//Questo blocco fa si che se il freezing supera un certo tempo, l' aggiornamento avvenga subito appena si torna a visualizzare la pagina.
			var prec_time = null;
			//Uso per salvare la lettura precedente.
			var interrupt_time = 5000;
			//ogni quanto faccio il test (in ms)
			var max_freeze_time = 30000 //tempo massimo di freezing oltre il quale aggiornare appena si torna a visualizzare la pagina.

		}
		//torna 1 se si è superato il tempo max di freezing
		function checkFreeze() {
			var ret = 0;
			if (prec_time) {
				var actual_time = new Date().getTime();
				if ((actual_time - prec_time) > (max_freeze_time - interrupt_time)) {
					ret = 1
				}
				prec_time = actual_time;
			}
			return ret;
		}//close function checkFreeze()

	} //close $.fn.update
})(jQuery);
