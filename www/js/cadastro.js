
$(function() {

  $("#cadastroForm input,#cadastroForm textarea").jqBootstrapValidation({
//	$("#cadastroForm input").jqBootstrapValidation({
    preventSubmit: true,
    submitError: function($form, event, errors) {
      // additional error messages or events
    },
    submitSuccess: function($form, event) {
      event.preventDefault(); // prevent default submit behaviour
      // get values from FORM
      var nome_pessoa = $("input#nome_pessoa").val();
      var documento_pessoa = $("input#documento_pessoa").val();
      var email_pessoa = $("input#email_pessoa").val();
      var telefone_pessoa = $("input#telefone_pessoa").val();
      $this = $("#enviaCadastroBotao");
      $this.prop("disabled", true); // Disable submit button until AJAX call is complete to prevent duplicate messages
    alert("antes do ajax");
	  $.ajax({
        url: "././php/cadastro.php",
        type: "POST",
        data: {
          nome_pessoa: nome_pessoa,
		      documento_pessoa: documento_pessoa,
	    	  email_pessoa: email_pessoa,
		      telefone_pessoa: telefone_pessoa},
        cache: false,
        success: function() {
          // Success message
          $('#sucessocadastro').html("<div class='alert alert-success'>");
          $('#sucessocadastro > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            .append("</button>");
          $('#sucessocadastro > .alert-success')
            .append("<strong>Seu cadastro foi enviado. Em seguida Você receberá um e-mail para finaliza-lo. Obrigado! </strong>");
          $('#sucessocadastro > .alert-success')
            .append('</div>');
          //clear all fields
          $('#cadastroForm').trigger("reset");
        },
        error: function( msg ) {
          // Fail message
          $('#sucessocadastro').html("<div class='alert alert-danger'>");
          $('#sucessocadastro > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            .append("</button>");
          $('#sucessocadastro > .alert-danger').append($("<strong>").text(msg+"<br>DDDDesculpe-me, algo de errado aconteceu. Por favor, tente novamente mais tarde."));
          $('#sucessocadastro > .alert-danger').append('</div>');
          //clear all fields
          $('#cadastroForm').trigger("reset");
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
$('#nome_pessoa').focus(function() {
  $('#sucessocadastro').html('');
});
