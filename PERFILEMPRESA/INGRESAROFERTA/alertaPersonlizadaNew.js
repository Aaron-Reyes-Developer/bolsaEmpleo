function alertaPersonalizada(titulo, icono){
    Swal.fire({
        position: "top-end",
        icon: icono,
        title: titulo,
        showConfirmButton: false,
        timer: 1500
    }).then((result) => {

        if (result.dismiss === Swal.DismissReason.timer) {
          window.location.reload();
        }
      });
};

