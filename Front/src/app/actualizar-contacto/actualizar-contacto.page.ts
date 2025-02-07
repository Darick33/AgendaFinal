import { Component, OnInit } from '@angular/core';
import { AccesoService } from '../service/acceso.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-actualizar-contacto',
  templateUrl: './actualizar-contacto.page.html',
  styleUrls: ['./actualizar-contacto.page.scss'],
  standalone: false,

})
export class ActualizarContactoPage implements OnInit {
  contacto: any = [];

  cod_contacto: string = "";
  text_nombre: string = "";
  text_apellido: string = "";
  text_telefono: string = "";
  text_correo: string = "";
  constructor(
    private servicio: AccesoService,
    private navCtrl: NavController
  ) {
    this.servicio.getSession("cod_contacto").then((res: any) => {
      this.cod_contacto = res;
      console.log(this.cod_contacto);
      this.cargarDatos();
    });
  }

  cargarDatos() {
    let datos = {
      "accion": "datosContacto",
      "cod_contacto": this.cod_contacto,
    }
    console.log(datos);
    this.servicio.postData(datos).subscribe((res: any) => {
      console.log(res);
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
  ngOnInit() {
  }
  guardar() {
    let datos = {
      "accion": "actualizarContacto",
      "cod_contacto": this.cod_contacto,
      "nombre": this.text_nombre,
      "apellido": this.text_apellido,
      "telefono": this.text_telefono,
      "correo": this.text_correo
    }
    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.estado) {
        this.servicio.showToast(res.mensaje, 3000, 'success');
        this.navCtrl.navigateRoot('/menu');
      } else {
        this.servicio.showToast(res.mensaje, 3000, 'danger');
      }
    });
  }
  cancelar() {
    this.navCtrl.navigateRoot("/menu");
  }

  eliminar() { }
}
