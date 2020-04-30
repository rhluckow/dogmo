function loadCamera(){
	//Captura elemento de vídeo
	var video = document.querySelector("#webCamera");
	var imagem = document.querySelector("#imagemConvertida");

	imagem.setAttribute('hidden', '');

		//As opções abaixo são necessárias para o funcionamento correto no iOS
		video.setAttribute('autoplay', '');
	  video.setAttribute('muted', '');
	  video.setAttribute('playsinline', '');
	    //--

	//Verifica se o navegador pode capturar mídia
	if (navigator.mediaDevices.getUserMedia) {
		navigator.mediaDevices.getUserMedia({audio: false, video: {facingMode: 'user'}})
		.then( function(stream) {
			//Definir o elemento víde a carregar o capturado pela webcam
			video.srcObject = stream;
		})
		.catch(function(error) {
			alert("Oooopps... Falhou :'(");
		});
	}
}

function desligacamera(){

	var video = document.querySelector("#webCamera");
	document.getElementById("webCamera").hidden = true;


//	navigator.mediaDevices.getUserMedia({video: false})
//	      .then(handleSuccess);

//	var videoTracks = stream.getVideoTracks();
//	videoTracks.forEach( function(track) { track.stop() } );

}


function takeSnapShot(){
	//Captura elemento de vídeo
	var video = document.querySelector("#webCamera");
	//Criando um canvas que vai guardar a imagem temporariamente
	var canvas = document.createElement('canvas');
	canvas.width = video.videoWidth;
	canvas.height = video.videoHeight;
	var ctx = canvas.getContext('2d');

	//Desnehando e convertendo as minensões
	ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

	//Criando o JPG
	var dataURI = canvas.toDataURL('image/jpeg'); //O resultado é um BASE64 de uma imagem.
	document.querySelector("#base_img").value = dataURI;

	sendSnapShot(dataURI); //Gerar Imagem e Salvar Caminho no Banco
}

function sendSnapShot(base64){
	var request = new XMLHttpRequest();
	var hash_parte = document.getElementById('hash_parte').value;
		request.open('POST', '././php/tirafoto.php', true);
		request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
		request.onload = function() {
			console.log(request);
			if (request.status >= 200 && request.status < 400) {
				//Colocar o caminho da imagem no SRC
				var data = JSON.parse(request.responseText);

				//verificar se houve erro
				if(data.error){
					alert(data.error);
					return false;
				}

				//Mostrar informações
				document.querySelector("#imagemConvertida").setAttribute("src", "php/"+data.img);
				document.querySelector("#caminhoImagem a").setAttribute("href", "php/"+data.img);
//				document.querySelector("#caminhoImagem a").innerHTML = data.img.split("/")[1];
				document.querySelector("#caminhoImagem a").innerHTML = "php/"+data.img;
			} else {
				alert( "Erro ao salvar. Tipo:" + request.status );
			}
		};

		request.onerror = function() {
		 	alert("Erro ao salvar. Back-End inacessível.");
		}

		request.send("hash_parte="+hash_parte+"&base_img="+base64); // Enviar dados

		desligacamera();

		document.getElementById("imagemConvertida").hidden = false;
		document.getElementById("botaotentarnovamente").hidden = false;
		document.getElementById("botaobaterfoto").hidden = true;

}

function tentarnovamente() {
	document.querySelector("#imagemConvertida").setAttribute("src", "");
	document.getElementById("webCamera").hidden = false;
	document.getElementById("webCamera").setAttribute("autoplay", "true");
	document.getElementById("imagemConvertida").hidden = true;
	document.getElementById("botaoligarcamera").hidden = true;
	document.getElementById("botaobaterfoto").hidden = false;
	document.getElementById("botaotentarnovamente").hidden = true;

}

function ligarcamera() {
	loadCamera();
	document.getElementById("webCamera").hidden = false;
	document.getElementById("webCamera").setAttribute("autoplay", "true");
	document.getElementById("imagemConvertida").hidden = true;
	document.getElementById("botaoligarcamera").hidden = true;
	document.getElementById("botaobaterfoto").hidden = false;

}

//loadCamera();
