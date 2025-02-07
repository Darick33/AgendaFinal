import { Component, OnInit } from '@angular/core';
import { NavController } from '@ionic/angular';
import { AccesoService } from '../service/acceso.service';

@Component({
  selector: 'app-eliminar-contacto',
  templateUrl: './eliminar-contacto.page.html',
  styleUrls: ['./eliminar-contacto.page.scss'],
  standalone: false,
})
export class EliminarContactoPage implements OnInit {

  contacto: any = [];

  cod_contacto: string = "";
  text_nombre: string = "";
  text_apellido: string = "";
  text_telefono: string = "";
  text_correo: string = "";
  public botones = [
    {
      text: "NO",
      role: "alert-button-cancel",
      handler: () => {
        this.cancelar();
      },
    },
    {
      text: "Si",
      role: "alert-button-confirm",
      handler: () => {
        this.eliminar();
      },
    },
  ]
  constructor(
    private navCtrl: NavController,
    private servicio: AccesoService
  ) {

    this.servicio.getSession("cod_contacto").then((res: any) => {
      this.cod_contacto = res;
      console.log(this.cod_contacto);
      this.cargarDatos();
    });
  }

  ngOnInit() {
  }

  cancelar() {
    this.navCtrl.navigateRoot("/menu");
  }
  eliminar() {
    let datos = {
      "accion": "eliminarContacto",
      "cod_contacto": this.cod_contacto
    }
    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.estado) {
        this.servicio.showToast(res.mensaje, 3000, 'danger');
        this.navCtrl.navigateRoot("/menu");
      } else {
        this.servicio.showToast(res.mensaje, 3000, 'danger');
      }
    })
  }
  cargarDatos() {
    let datos = {
      "accion": "datosContacto",
      "cod_contacto": this.cod_contacto,
    }
    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.estado) {
        this.contacto = res.data;
        this.text_nombre = this.contacto.nombre;
        this.text_apellido = this.contacto.apellido;
        this.text_telefono = this.contacto.telefono;
        this.text_correo = this.contacto.correo;
      } else {
        this.servicio.showToast(res.mensaje, 3000, 'danger');
      }
    })
  }
}
