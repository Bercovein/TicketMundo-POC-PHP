
function prevent(name){

    document.querySelector(name).addEventListener('submit', function(e) {
        var form = this;
    		      
        e.preventDefault();
    		      
        swal({
            title: "¿Esta seguro?",
            text: "Los cambios podrían no deshacerse mas tarde...",
            icon: "warning",
            buttons: [
               'Cancelar',
               'Aceptar'
    		          ],
            dangerMode: true,
        }).then(function(isConfirm) {
        if (isConfirm) {
            form.submit();
        } else {
            swal("", "No han habido cambios", "success");
            }
        });
    });
}