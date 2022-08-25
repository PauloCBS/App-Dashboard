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
})}

)