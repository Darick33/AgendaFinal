import { Component, OnInit } from '@angular/core';
import { AccesoService } from '../service/acceso.service';
import { ModalController } from '@ionic/angular';

@Component({
  selector: 'app-cuenta',
  templateUrl: './cuenta.page.html',
  styleUrls: ['./cuenta.page.scss'],
  standalone: false
})
export class CuentaPage implements OnInit {
  txt_cedula: string = "";
  txt_nombre: string = "";
  txt_apellido: string = "";
  txt_correo: string = "";
  txt_clave: string = "";
  txt_confirmarClave: string = "";
  mensaje: string = "";
  constructor(
    private servicio: AccesoService,
    private modalCtrl: ModalController
  ) { }

  ngOnInit() {
  }
  cerrarModal() {
    this.modalCtrl.dismiss();
  }
  
  vcedula() {
    let datos = {
      accion: 'vcedula',
      cedula: this.txt_cedula
    }
    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.etado) {
        this.txt_cedula = "";
        this.servicio.showToast(res.mensaje, 3000, 'success');
      }
    })
  }
  registrar() {
    if (this.txt_cedula == '' ||
      this.txt_nombre == '' || this.txt_apellido == '' ||
      this.txt_correo == '' || this.txt_clave == '' || this.txt_confirmarClave == '') {
      this.servicio.showToast('Todos los campos son obligatorios', 3000, 'warning');
    } else {
      let datos = {
        accion: 'cuenta',
        cedula: this.txt_cedula,
        nombre: this.txt_nombre,
        apellido: this.txt_apellido,
        correo: this.txt_correo,
        clave: this.txt_clave
      };
      this.servicio.postData(datos).subscribe(
        (response: any) => {
          if (response.estado) {
            this.servicio.showToast(response.mensaje, 3000, 'success');
            this.modalCtrl.dismiss();
          } else {
            this.servicio.showToast(response.mensaje, 3000, 'danger');
          }
        },
        (error) => {
          console.log(error);
        }
      );

    }
  }
  vclave() {
    if (this.txt_clave != this.txt_confirmarClave) {
      this.mensaje = 'Las claves no coinciden';
    } else {
      this.mensaje = '';
    }
  }
  cancelar() {
    this.modalCtrl.dismiss();
  }
}
