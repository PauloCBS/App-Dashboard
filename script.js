$(document).ready(() => {
	
$('#documentacao').on('click',  () => {
    //$('#pagina').load('documentacao.html') -> uma forma de fazer

    //requisicao com GET
    $.get('documentacao.html', data => {
        $('#pagina').html(data)
    })
})

$('#suporte').on('click',  () => {
    $('#pagina').load('suporte.html')
})

//AJAX

$('#competencia').on('change', e => {
   //console.log( $(e.target).val());

   let competencia = $(e.target).val()
   console.log(competencia)

   $.ajax({
    //info basicas para a requisicao http: metodo, url, dados, sucesso, erro.
        type: 'GET',
        url: 'app.php',
        data: `competencia= ${competencia}`,
        dataType: 'json',
        success: dados => {
            $('#numeroVendas').html(dados.numeroVendas);
            $('#totalVendas').html(dados.totalVendas);
            $('#clientesAtivos').html(dados.clientesAtivos)

        },
        error: erro => {console.log(erro)}
   })
})

})