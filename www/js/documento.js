
$(function() {

  $("#documentoForm input,#documentoForm textarea").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, event, errors) {
      // additional error messages or events
    },
    submitSuccess: function($form, event) {
      event.preventDefault(); // prevent default submit behaviour
	  
      // get values from FORM
	  
      var doc_pessoa        = $("input#doc_pessoa").val();
      var titulo_documento  = $("input#titulo_documento").val();
	  var nome_destino      = $("input#nome_destino").val();
      var documento_destino = $("input#documento_destino").val();
      var email_destino     = $("input#email_destino").val();
      var fone_destino      = $("input#fone_destino").val();
      var documento         = $("textarea#documento").val();

      $this = $("#enviaDocumentoBotao");
      $this.prop("disabled", true); // Disable submit button until AJAX call is complete to prevent duplicate messages
      
	  $.ajax({
        url: "././php/documento.php",
        type: "POST",
        data: {
		  doc_pessoa: doc_pessoa,
		  titulo_documento: titulo_documento,
		  nome_destino: nome_destino,
		  documento_destino: documento_destino,
		  email_destino: email_destino,
		  fone_destino: fone_destino,
		  documento: documento},
        cache: false,
        success: function( msg ) {
		  
			if (msg == "ok") {
				
				// Success message
		  
				$('#sucessodocumento').html("<div class='alert alert-success'>");
				$('#sucessodocumento > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
					.append("</button>");
				$('#sucessodocumento > .alert-success')
					.append("<strong> Seu documento foi enviado. Você receberá uma confirmação por e-mail. Obrigado! </strong>");
				$('#sucessodocumento > .alert-success')
					.append('</div>');
				//clear all fields
				$('#documentoForm').trigger("reset");
			}
			else
			{
				  // Fail message
				  $('#sucessodocumento').html("<div class='alert alert-danger'>asdfasdfasdf");
				  $('#sucessodocumento > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
					.append("</button>");
				  $('#sucessodocumento > .alert-danger').append($("<strong>").text(msg));
				  $('#sucessodocumento > .alert-danger').append('</div>');
				  
				
			}

        },
        error: function() {
          // Fail message
          $('#sucessodocumento').html("<div class='alert alert-danger'>asdfasdfasdf");
          $('#sucessodocumento > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            .append("</button>");
          $('#sucessodocumento > .alert-danger').append($("<strong>").text("Desculpe-me, algo de errado aconteceu. Por favor, tente novamente mais tarde."));
          $('#sucessodocumento > .alert-danger').append('</div>');
          //clear all fields
          $('#documentoForm').trigger("reset");
        },
        complete: function() {
          setTimeout(function() {
            $this.prop("disabled", false); // Re-enable submit button when AJAX call is complete
          }, 1000);
        }
      });
    },
    filter: function() {
      return $(this).is(":visible");
    },
  });

  $("a[data-toggle=\"tab\"]").click(function(e) {
    e.preventDefault();
    $(this).tab("show");
  });
});

/*When clicking on Full hide fail/success boxes */
$('#documento_pessoa').focus(function() {
  $('#sucessodocumento').html('');
});
