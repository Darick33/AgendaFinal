import { Component, OnInit } from '@angular/core';
import { AccesoService } from '../service/acceso.service';
import { NavController } from '@ionic/angular';

@Component({
  selector: 'app-rclave',
  templateUrl: './rclave.page.html',
  styleUrls: ['./rclave.page.scss'],
  standalone: false
})
export class RclavePage implements OnInit {
  nuevaClave: any;
  mostrarCambioClave: boolean = false;
  preguntasRespuestas: any[] = [];
  usuario: any;
  respuestas: any[] = [];
  usuariov: boolean = true
  verClave: boolean = false
  

  constructor(private servicio: AccesoService, private navCtrl: NavController) {}

  ngOnInit() {}

  regresar() {
    this.navCtrl.navigateBack("/home");
  }

  // Verifica el usuario y obtiene todas las preguntas de seguridad
  verificarUsuario() {
    // Aseguramos que las respuestas y preguntas anteriores se limpien antes de una nueva verificación
    this.preguntasRespuestas = [];
    this.respuestas = [];
    const datos = {
      accion: 'todasLasPreguntasSeguridad',
      ci: this.usuario
    };

    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.estado) {
        console.log(res);
        this.preguntasRespuestas = res.preguntas_respuestas;
        this.respuestas = new Array(this.preguntasRespuestas.length).fill('');
        this.usuariov  = false
        this.verClave = true
      } else {
        this.servicio.showToast(res.mensaje, 2000, 'danger');
      }
    });
  }

  // Verifica todas las respuestas ingresadas por el usuario
  verificarRespuestas() {
    let respuestasCorrectas = true;

    for (let i = 0; i < this.preguntasRespuestas.length; i++) {
      if (this.preguntasRespuestas[i].respuesta !== this.respuestas[i]) {
        respuestasCorrectas = false;
        break;
      }
    }

    if (respuestasCorrectas) {
      this.mostrarCambioClave = true;
      this.servicio.showToast("Respuestas correctas. Ahora puedes cambiar tu contraseña.", 2000, 'success');
      this.verClave = false
    } else {
      this.servicio.showToast("Una o más respuestas son incorrectas. Inténtalo de nuevo.", 2000, 'warning');
    }
  }

  // Cambia la contraseña
  async cambiarClave() {
    const id = this.usuario;
    const datos = {
      accion: 'cambiarClave',
      id: id,
      clave: this.nuevaClave
    };

    this.servicio.postData(datos).subscribe((res: any) => {
      if (res.estado) {
        this.servicio.showToast("Clave cambiada con éxito", 2000, 'success');
      } else {
        this.servicio.showToast(res.mensaje, 2000, 'danger');
      }
    });
    this.navCtrl.navigateBack("/home");
  }
}
