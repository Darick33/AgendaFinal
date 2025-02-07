import { Component, OnInit } from '@angular/core';
import { AccesoService } from '../service/acceso.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-contacto',
  templateUrl: './contacto.page.html',
  styleUrls: ['./contacto.page.scss'],
  standalone: false,
})
export class ContactoPage implements OnInit {

  cod_persona: string = "";
  text_nombre: string = "";
  text_apellido: string = "";
  text_telefono: string = "";
  text_correo: string = "";
  mensaje: string = "";

  constructor(
    private servicio: AccesoService,
    private navCtrl: NavController
  ) {
    this.servicio.getSession("idpersona").then((res: any) => {
      this.cod_persona = res;
    });
  }

  verificarNumero() {
    let datos = {
      "accion": "verificarTelefono",
      "cod_persona": this.cod_persona,
      "telefono": this.text_telefono
    }
    this.servicio.postData(datos).subscribe((res: any) => {
      this.mensaje = res.mensaje;
    });
  }
  ngOnInit() {
  }

  cancelar() {
    this.navCtrl.back();


  }

  guardar() {

    let datos = {
      "accion": "nuevoContacto",
      "cod_persona": this.cod_persona,
      "nombre": this.text_nombre,
      "apellido": this.text_apellido,
      "telefono": this.text_telefono,
      "correo": this.text_correo,
    }
    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.estado) {

        this.servicio.showToast(res.mensaje, 3000, 'success');
        this.navCtrl.back();
      } else {
        this.servicio.showToast(res.mensaje, 3000, 'danger');
      }
    });
  }
}
